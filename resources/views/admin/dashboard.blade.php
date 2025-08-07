@extends('layouts.admin')

@section('content')

  <!-- Container -->
  <div class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-6 md:py-10 mt-4">

    <!-- Grid: charts + sidecards -->
    <div class="grid gap-6 mb-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
    <!-- Main Bar Chart -->
    <section class="lg:col-span-2 dash-card p-4 md:p-6">
      <header class="flex flex-col sm:flex-row justify-between items-center mb-3 gap-2">
      <h2 class="text-2xl font-bold">Income & Expenses (This Week)</h2>
      <button class="text-gray-400 hover:text-gray-600">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="6" r="1.5" />
        <circle cx="12" cy="12" r="1.5" />
        <circle cx="12" cy="18" r="1.5" />
        </svg>
      </button>
      </header>
      <div class="relative h-64 md:h-80"><canvas id="mainBarChart"></canvas></div>
    </section>

    <!-- Sidecards stack -->
    <div class="flex flex-col gap-6">
      <!-- Payment Distribution -->
      <section class="dash-card p-4 md:p-6 flex flex-col items-center text-center">
      <h3 class="text-xl font-semibold mb-2">Payment Distribution</h3>
      <div class="relative h-48 md:h-56 w-full"><canvas id="trafficDonut"></canvas></div>
      <p class="text-2xl font-bold mt-4">₹{{ number_format($summaryStats['currentMonthIncome'], 2) }}</p>
      @php
        $incomeChangePercent = $summaryStats['lastMonthIncome'] > 0 ? (($summaryStats['currentMonthIncome'] - $summaryStats['lastMonthIncome']) / $summaryStats['lastMonthIncome']) * 100 : 0;
      @endphp
      <p class="flex items-center {{ $incomeChangePercent >= 0 ? 'text-emerald-500' : 'text-rose-500' }} text-sm">
        <svg class="w-6 h-4" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" d="{{ $incomeChangePercent >= 0 ? 'M5 10l7-7 7 7M12 3v18' : 'M19 14l-7 7-7-7M12 21V3' }}" />
        </svg>
        {{ $incomeChangePercent >= 0 ? '+' : '' }}{{ number_format($incomeChangePercent, 1) }}% this month
      </p>
      <div class="flex gap-4 text-xs mt-3 flex-wrap justify-center">
        @foreach($paymentDistribution['labels'] as $index => $label)
        <span class="flex items-center">
          <span class="inline-block w-3 h-3 mr-1 rounded-full" style="background-color: {{ $paymentDistribution['colors'][$index] ?? '#4f46e5' }}"></span>
          {{ $label }}
        </span>
        @endforeach
      </div>
      </section>
    </div>
    </div>

    <!-- Profit & Loss Section -->
    <div class="grid gap-6 mb-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
      <!-- Total Income -->
      <section class="dash-card p-4 md:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-base text-gray-500">Total Income</p>
            <p class="text-3xl font-bold text-emerald-600">₹{{ number_format($summaryStats['currentMonthIncome'], 2) }}</p>
            <p class="text-sm {{ $incomeChangePercent >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
              {{ $incomeChangePercent >= 0 ? '+' : '' }}{{ number_format($incomeChangePercent, 1) }}% vs last month
            </p>
          </div>
          <div class="bg-emerald-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
          </div>
        </div>
      </section>
      <!-- Total Expenses -->
      <section class="dash-card p-4 md:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-base text-gray-500">Total Expenses</p>
            <p class="text-3xl font-bold text-rose-600">₹{{ number_format($summaryStats['currentMonthExpenses'], 2) }}</p>
            @php
              $expenseChangePercent = $summaryStats['lastMonthExpenses'] > 0 ? (($summaryStats['currentMonthExpenses'] - $summaryStats['lastMonthExpenses']) / $summaryStats['lastMonthExpenses']) * 100 : 0;
            @endphp
            <p class="text-sm {{ $expenseChangePercent >= 0 ? 'text-rose-500' : 'text-emerald-500' }}">
              {{ $expenseChangePercent >= 0 ? '+' : '' }}{{ number_format($expenseChangePercent, 1) }}% vs last month
            </p>
          </div>
          <div class="bg-rose-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
        </div>
      </section>
      <!-- Net Profit/Loss -->
      <section class="dash-card p-4 md:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-base text-gray-500">Net Profit/Loss</p>
            @php
              $netProfit = $summaryStats['currentMonthIncome'] - $summaryStats['currentMonthExpenses'];
            @endphp
            <p class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
              ₹{{ number_format($netProfit, 2) }}
            </p>
            <p class="text-sm {{ $netProfit >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
              {{ $netProfit >= 0 ? 'Profit' : 'Loss' }} this month
            </p>
          </div>
          <div class="{{ $netProfit >= 0 ? 'bg-emerald-100' : 'bg-rose-100' }} p-3 rounded-full">
            <svg class="w-6 h-6 {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
      </section>
      <!-- Profit Margin -->
      <section class="dash-card p-4 md:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-base text-gray-500">Profit Margin</p>
            @php
              $profitMargin = $summaryStats['currentMonthIncome'] > 0 ? 
                ($netProfit / $summaryStats['currentMonthIncome']) * 100 : 0;
            @endphp
            <p class="text-2xl font-bold {{ $profitMargin >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
              {{ number_format($profitMargin, 1) }}%
            </p>
            <p class="text-sm text-gray-500">
              of total income
            </p>
          </div>
          <div class="{{ $profitMargin >= 0 ? 'bg-emerald-100' : 'bg-rose-100' }} p-3 rounded-full">
            <svg class="w-6 h-6 {{ $profitMargin >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
      </section>
    </div>

    <!-- Detailed Profit & Loss Breakdown -->
    <div class="grid gap-6 mb-8 grid-cols-1 md:grid-cols-2">
      <!-- Income Breakdown -->
      <section class="dash-card p-4 md:p-6">
        <h3 class="text-xl font-semibold mb-4">Income Breakdown</h3>
        @if(count($incomeBreakdown) > 0)
          <div class="space-y-3">
            @php
              $totalIncome = array_sum(array_column($incomeBreakdown, 'total'));
            @endphp
            @foreach($incomeBreakdown as $income)
              @php
                $percentage = $totalIncome > 0 ? ($income['total'] / $totalIncome) * 100 : 0;
              @endphp
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                  <span class="text-sm font-medium">{{ $income['category'] }}</span>
                </div>
                <div class="text-right">
                  <div class="text-sm font-semibold">₹{{ number_format($income['total'], 2) }}</div>
                  <div class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</div>
                </div>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-gray-500 text-center py-4">No income data for this month</p>
        @endif
      </section>

      <!-- Expense Breakdown -->
      <section class="dash-card p-4 md:p-6">
        <h3 class="text-xl font-semibold mb-4">Expense Breakdown</h3>
        @if(count($expenseBreakdown) > 0)
          <div class="space-y-3">
            @php
              $totalExpenses = array_sum(array_column($expenseBreakdown, 'total'));
            @endphp
            @foreach($expenseBreakdown as $expense)
              @php
                $percentage = $totalExpenses > 0 ? ($expense['total'] / $totalExpenses) * 100 : 0;
              @endphp
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-3 h-3 bg-rose-500 rounded-full"></div>
                  <span class="text-sm font-medium">{{ $expense['category'] }}</span>
                </div>
                <div class="text-right">
                  <div class="text-sm font-semibold">₹{{ number_format($expense['total'], 2) }}</div>
                  <div class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</div>
                </div>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-gray-500 text-center py-4">No expense data for this month</p>
        @endif
      </section>
    </div>

    <!-- Grid: timeline + table -->
    <div class="grid gap-6 grid-cols-1 md:grid-cols-2">
      <!-- Recent Activity Timeline -->
      <section class="dash-card p-4 md:p-6">
        <h3 class="text-xl font-semibold mb-4">Recent Activity</h3>
        <div class="space-y-4">
          @foreach($recentActivities as $activity)
          <div class="flex items-start space-x-3">
            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
            <div class="flex-1">
              <p class="text-sm font-medium">{{ $activity['description'] }}</p>
              <p class="text-xs text-gray-500">
                {{ isset($activity['created_at']) ? \Illuminate\Support\Carbon::parse($activity['created_at'])->format('Y-m-d H:i') : 'N/A' }}
              </p>
            </div>
          </div>
          @endforeach
        </div>
      </section>
      <!-- Quick Actions (optional, keep hidden on mobile) -->
      <!-- <section class="dash-card p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
          <a href="/admin/users/create" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="text-sm font-medium">Add Client</span>
          </a>
          <a href="/admin/invoices/create" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-sm font-medium">Create Invoice</span>
          </a>
          <a href="/admin/income/create" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
            <span class="text-sm font-medium">Record Income</span>
          </a>
          <a href="/admin/expense/create" class="flex items-center p-3 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors">
            <svg class="w-5 h-5 text-rose-600 mr-2" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-sm font-medium">Record Expense</span>
          </a>
        </div>
      </section> -->
    </div>

  </div>

  <!-- Charts setup -->
  <script>
    // Bar Chart - Income vs Expenses
    new Chart(document.getElementById('mainBarChart'), {
    type: 'bar',
    data: {
      labels: @json($weeklyData['labels']),
      datasets: [
      { 
        label: 'Income', 
        data: @json($weeklyData['income']), 
        backgroundColor: '#4f46e5', 
        borderRadius: 8, 
        barPercentage: 0.55 
      },
      { 
        label: 'Expenses', 
        data: @json($weeklyData['expenses']), 
        backgroundColor: '#fb7185', 
        borderRadius: 8, 
        barPercentage: 0.55 
      }
      ]
    },
    options: {
      responsive: true, 
      maintainAspectRatio: false,
      plugins: { 
        legend: { 
          display: true,
          position: 'top'
        } 
      },
      scales: {
      x: { grid: { display: false } },
      y: { 
        beginAtZero: true, 
        grid: { color: '#f3f4f6' },
        ticks: {
          callback: function(value) {
            return '₹' + value.toLocaleString();
          }
        }
      }
      }
    }
    });

    // Doughnut - Payment Distribution
    new Chart(document.getElementById('trafficDonut'), {
    type: 'doughnut',
    data: {
      labels: @json($paymentDistribution['labels']),
      datasets: [{
      data: @json($paymentDistribution['data']),
      backgroundColor: @json($paymentDistribution['colors']),
      borderWidth: 0
      }]
    },
    options: { 
      cutout: '70%', 
      plugins: { 
        legend: { 
          display: false 
        } 
      }, 
      responsive: true, 
      maintainAspectRatio: false 
    }
    });
  </script>
@endsection