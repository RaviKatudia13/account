<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class VendorCategoryController extends Controller
{
    public function index()
    {
        $categories = VendorCategory::where('user_id', Auth::id())->get();
        return view('admin.vendor_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('vendor_categories')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
        ]);
        VendorCategory::create(['name' => $request->name]);
        return redirect()->route('admin.vendor-categories.index')->with('success', 'Category added successfully!');
    }

    public function edit($id)
    {
        $category = VendorCategory::findOrFail($id);
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.vendor_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = VendorCategory::findOrFail($id);
        $request->validate([
            'name' => [
                'required',
                Rule::unique('vendor_categories')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($category->id),
            ],
        ]);
        $category->name = $request->name;
        $category->save();
        return redirect()->route('admin.vendor-categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(VendorCategory $vendor_category)
    {
        $vendor_category->delete();
        return redirect()->route('admin.vendor-categories.index')->with('success', 'Category deleted successfully.');
    }
} 