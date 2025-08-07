<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $clients = Client::with('category', 'invoices.payments')->where('user_id', Auth::id())->get();
        $categories = Category::where('user_id', Auth::id())->get();
        return view('admin.users', compact('clients', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('clients')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'mobile' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'gst_registered' => 'required|boolean',
            'gstin' => 'nullable|string|max:15',
        ]);
        $validated['user_id'] = Auth::id();
        Client::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Client added successfully!');
    }

    public function update(Request $request, Client $client)
    {
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('clients')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($client->id),
            ],
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'mobile' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'gst_registered' => 'required|boolean',
            'gstin' => 'nullable|string|max:15',
        ]);

        $client->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Client updated successfully!');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }
        $client->delete();

        return redirect()->route('admin.users.index')->with('success', 'Client deleted successfully!');
    }
}
