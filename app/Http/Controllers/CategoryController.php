<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        // Ambil data kategori, jika ada parameter pencarian, filter berdasarkan nama kategori
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('nama_kategori', 'like', '%' . $search . '%');
        })->get();
    
        return view('pages.admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('pages.admin.category.create');
    }

    public function store(Request $request)
    {
        // Validate the input and add a custom error message for the 'unique' validation
        $request->validate([
            'nama_kategori' => 'required|unique:categories|max:255',
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah ada dalam data', // Custom error message
        ]);
    
        // If validation passes, create the new category
        Category::create($request->all());
    
        // Redirect with success message
        return redirect()->route('admin.category.index')->with('success', 'Category added successfully.');
    }
    

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:categories|max:255',
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah ada dalam data', // Custom error message
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');

    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');

    }
}
