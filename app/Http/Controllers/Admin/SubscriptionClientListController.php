<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionClientList;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubscriptionClientListController extends Controller
{
    public function index()
    {
        $clients = SubscriptionClientList::where('user_id', Auth::id())->paginate(15);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('admin.subscription_clients.index', compact('clients', 'categories'));
    }

    public function create()
    {
        return view('admin.subscription_clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('subscriptions_client_list')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
            'company_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gst_registered' => 'boolean',
            'gstin' => 'nullable|string|max:15',
            'payment_mode' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        $validated['user_id'] = Auth::id();
        SubscriptionClientList::create($validated);
        return redirect()->route('admin.subscription-clients.index')->with('success', 'Client added successfully.');
    }

    public function edit($id)
    {
        $client = SubscriptionClientList::findOrFail($id);
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.subscription_clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = SubscriptionClientList::findOrFail($id);
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('subscriptions_client_list')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($client->id),
            ],
            'company_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gst_registered' => 'boolean',
            'gstin' => 'nullable|string|max:15',
            'payment_mode' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        $client->update($validated);
        return redirect()->route('admin.subscription-clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy($id)
    {
        $client = SubscriptionClientList::findOrFail($id);
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }
        $client->delete();
        return redirect()->route('admin.subscription-clients.index')->with('success', 'Client deleted successfully.');
    }
} 