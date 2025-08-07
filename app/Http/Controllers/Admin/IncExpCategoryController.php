<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IncExpCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class IncExpCategoryController extends Controller
{
    public function index()
    {
        $categories = IncExpCategory::where('user_id', Auth::id())->get();
        return view('admin.inc_exp_category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('inc_exp_categories')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
        ]);
        IncExpCategory::create(['name' => $request->name]);
        return redirect()->route('admin.inc-exp-category.index')->with('success', 'Category added successfully!');
    }

    public function edit($id)
    {
        $category = IncExpCategory::findOrFail($id);
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.inc_exp_category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = IncExpCategory::findOrFail($id);
        $request->validate([
            'name' => [
                'required',
                Rule::unique('inc_exp_categories')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($category->id),
            ],
        ]);
        $category->name = $request->name;
        $category->save();
        return redirect()->route('admin.inc-exp-category.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(IncExpCategory $inc_exp_category)
    {
        $inc_exp_category->delete();
        return redirect()->route('admin.inc-exp-category.index')->with('success', 'Category deleted successfully.');
    }
} 