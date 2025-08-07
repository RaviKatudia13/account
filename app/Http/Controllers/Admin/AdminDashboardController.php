<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\IncomeExpense;
use App\Models\InternalTransfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\IncExpCategory;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $userCount = User::count(); // (Optional: keep as is if you want total users, or restrict to 1 for current user)
        $categoryCount = Category::where('user_id', Auth::id())->count();
        $clientCount = Client::where('user_id', Auth::id())->count();
        
        // Weekly data for bar chart (Income vs Expenses)
        $weeklyData = $this->getWeeklyIncomeExpenses();
        
        // Payment distribution for doughnut chart
        $paymentDistribution = $this->getPaymentDistribution();
        
        // Recent activities for timeline
        $recentActivities = $this->getRecentActivities();
        
        // Top paying clients
        $topClients = $this->getTopPayingClients();
        
        // Summary statistics
        $summaryStats = $this->getSummaryStats();
        
        // Detailed breakdown
        $incomeBreakdown = $this->getIncomeBreakdown();
        $expenseBreakdown = $this->getExpenseBreakdown();
        
        return view('admin.dashboard', compact(
            'userCount', 
            'categoryCount', 
            'clientCount',
            'weeklyData',
            'paymentDistribution',
            'recentActivities',
            'topClients',
            'summaryStats',
            'incomeBreakdown',
            'expenseBreakdown'
        ));
    }
    
    private function getWeeklyIncomeExpenses()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $incomeData = [];
        $expenseData = [];
        $labels = [];
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $labels[] = $date->format('D');
            
            // Get income for this day
            $income = IncomeExpense::where('type', 'income')
                ->where('user_id', Auth::id())
                ->whereDate('date', $date)
                ->sum('amount');
            $incomeData[] = $income;
            
            // Get expenses for this day
            $expense = IncomeExpense::where('type', 'expense')
                ->where('user_id', Auth::id())
                ->whereDate('date', $date)
                ->sum('amount');
            $expenseData[] = $expense;
        }
        
        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expenses' => $expenseData
        ];
    }
    
    private function getPaymentDistribution()
    {
        $payments = Payment::select('payment_mode', DB::raw('SUM(amount) as total'))
            ->where('user_id', Auth::id())
            ->whereNotNull('payment_mode')
            ->groupBy('payment_mode')
            ->get();
            
        $labels = [];
        $data = [];
        $colors = ['#4f46e5', '#fb7185', '#8b5cf6', '#f59e0b', '#10b981'];
        
        foreach ($payments as $index => $payment) {
            $paymentMethod = \App\Models\PaymentMethod::find($payment->payment_mode);
            $labels[] = $paymentMethod ? $paymentMethod->name : 'Unknown';
            $data[] = $payment->total;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }
    
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Recent payments
        $recentPayments = Payment::with(['invoice.client', 'vendor', 'employee'])
            ->where('user_id', Auth::id())
            ->orderByDesc('payment_date')
            ->take(10)
            ->get();
            
        foreach ($recentPayments as $payment) {
            $description = '';
            $type = 'payment';
            
            if ($payment->invoice) {
                $description = "Payment for Invoice #" . ($payment->invoice->invoice_number ?? '') . " of ₹" . number_format($payment->amount, 2);
            } elseif ($payment->vendor) {
                $description = "Payment to " . ($payment->vendor->name ?? 'Vendor') . " of ₹" . number_format($payment->amount, 2);
            } elseif ($payment->employee) {
                $description = "Payment to " . ($payment->employee->name ?? 'Employee') . " of ₹" . number_format($payment->amount, 2);
            } elseif ($payment->internal_transfer) {
                $description = "Internal transfer of ₹" . number_format($payment->amount, 2);
                $type = 'transfer';
            }
            
            $activities->push([
                'created_at' => $payment->created_at,
                'description' => $description,
                'type' => $type,
                'amount' => $payment->amount
            ]);
        }
        
        // Recent invoices
        $recentInvoices = Invoice::with('client')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($recentInvoices as $invoice) {
            $activities->push([
                'created_at' => $invoice->created_at,
                'description' => "New invoice #" . $invoice->invoice_number . " to " . ($invoice->client->name ?? 'Client'),
                'type' => 'invoice',
                'amount' => $invoice->total
            ]);
        }
        
        return $activities->sortByDesc('created_at')->take(10)->values();
    }
    
    private function getTopPayingClients()
    {
        return Client::withSum(['invoices' => function($query) {
            $query->where('user_id', Auth::id());
        }], 'total')
            ->where('user_id', Auth::id())
            ->orderBy('invoices_sum_total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($client) {
                return [
                    'name' => $client->name,
                    'company' => $client->company_name,
                    'total_paid' => $client->invoices_sum_total ?? 0,
                    'invoice_count' => $client->invoices()->where('user_id', Auth::id())->count(),
                    'status' => $client->invoices()->where('user_id', Auth::id())->where('status', 'paid')->count() > 0 ? 'active' : 'pending'
                ];
            });
    }
    
    private function getSummaryStats()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Current month stats
        $currentMonthIncome = IncomeExpense::where('type', 'income')
            ->where('user_id', Auth::id())
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->sum('amount');
            
        $currentMonthExpenses = IncomeExpense::where('type', 'expense')
            ->where('user_id', Auth::id())
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->sum('amount');
            
        // Last month stats for comparison
        $lastMonthIncome = IncomeExpense::where('type', 'income')
            ->where('user_id', Auth::id())
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');
            
        $lastMonthExpenses = IncomeExpense::where('type', 'expense')
            ->where('user_id', Auth::id())
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');
        
        return [
            'currentMonthIncome' => $currentMonthIncome,
            'currentMonthExpenses' => $currentMonthExpenses,
            'lastMonthIncome' => $lastMonthIncome,
            'lastMonthExpenses' => $lastMonthExpenses
        ];
    }
    
    private function getIncomeBreakdown()
    {
        $categories = IncExpCategory::where('user_id', Auth::id())->get();
        $breakdown = [];
        foreach ($categories as $category) {
            $total = IncomeExpense::where('type', 'income')
                ->where('user_id', Auth::id())
                ->where('inc_exp_category_id', $category->id)
                ->sum('amount');
            $breakdown[] = [
                'category' => $category->name,
                'total' => $total
            ];
        }
        return $breakdown;
    }
    
    private function getExpenseBreakdown()
    {
        $categories = IncExpCategory::where('user_id', Auth::id())->get();
        $breakdown = [];
        foreach ($categories as $category) {
            $total = IncomeExpense::where('type', 'expense')
                ->where('user_id', Auth::id())
                ->where('inc_exp_category_id', $category->id)
                ->sum('amount');
            $breakdown[] = [
                'category' => $category->name,
                'total' => $total
            ];
        }
        return $breakdown;
    }
}
