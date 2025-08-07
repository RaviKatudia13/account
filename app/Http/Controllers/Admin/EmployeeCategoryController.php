<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EmployeeCategoryController extends Controller
{
    public function index()
    {
        $categories = EmployeeCategory::where('user_id', Auth::id())->get();
        return view('admin.employee_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('employee_categories')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
        ]);
        EmployeeCategory::create(['name' => $request->name]);
        return redirect()->route('admin.employee-categories.index')->with('success', 'Category added successfully!');
    }

    public function edit($id)
    {
        $category = EmployeeCategory::findOrFail($id);
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.employee_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = EmployeeCategory::findOrFail($id);
        $request->validate([
            'name' => [
                'required',
                Rule::unique('employee_categories')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($category->id),
            ],
        ]);
        $category->name = $request->name;
        $category->save();
        return redirect()->route('admin.employee-categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(EmployeeCategory $employee_category)
    {
        $employee_category->delete();
        return redirect()->route('admin.employee-categories.index')->with('success', 'Category deleted successfully.');
    }
} 