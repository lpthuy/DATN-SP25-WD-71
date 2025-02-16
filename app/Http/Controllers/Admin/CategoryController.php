<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    // Hiển thị form thêm danh mục
    public function create()
    {
        $parentCategories = Category::whereNull('parent_category_id')->get(); // Lấy danh mục cha
        return view('admin.categories.create', compact('parentCategories'));
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:categories,sku',
            'parent_category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Lưu hình ảnh nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
        } else {
            $imagePath = null;
        }

        Category::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'parent_category_id' => $request->parent_category_id,
            'is_active' => $request->is_active ?? true,
            'image_url' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được thêm!');
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = Category::whereNull('parent_category_id')->where('id', '!=', $id)->get(); // Không cho tự chọn làm cha
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:categories,sku,' . $id,
            'parent_category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);

        // Cập nhật hình ảnh nếu có
        if ($request->hasFile('image')) {
            if ($category->image_url) {
                Storage::delete('public/' . $category->image_url);
            }
            $imagePath = $request->file('image')->store('category_images', 'public');
        } else {
            $imagePath = $category->image_url;
        }

        $category->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'parent_category_id' => $request->parent_category_id,
            'is_active' => $request->is_active ?? true,
            'image_url' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật!');
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image_url) {
            Storage::delete('public/' . $category->image_url);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Danh mục đã được xóa!');
    }
}
