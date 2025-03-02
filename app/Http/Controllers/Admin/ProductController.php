<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
    public function index() {
        $products = Product::with(['category', 'colors', 'sizes'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.products.create', compact('categories', 'colors', 'sizes'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0', // Giá chung
            'discount_price' => 'nullable|numeric|min:0|lt:price', // Giá giảm phải nhỏ hơn giá gốc
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'required|array',
        ]);
    
        // Lưu thông tin sản phẩm (trừ ảnh, size, màu)
        $data = $request->except(['images', 'variants']);
        $imagePaths = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            $data['image'] = implode(',', $imagePaths);
        }
    
        $product = Product::create($data);
    
        // Lưu các biến thể sản phẩm (size, màu, số lượng, giá chung)
        foreach ($request->variants as $sizeId => $colors) {
            foreach ($colors as $colorId => $variant) {
                if (isset($variant['selected'])) {
                    $product->variants()->create([
                        'size_id' => $sizeId,
                        'color_id' => $colorId,
                        'stock_quantity' => $variant['stock_quantity'] ?? 0,
                        'price' => $request->price, // Áp dụng chung giá
                        'discount_price' => $request->discount_price ?? null, // Thêm giá giảm nếu có
                    ]);
                }
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo!');
    }
    
    
    
    public function edit(Product $product) {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.products.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price', // Giá giảm phải nhỏ hơn giá gốc
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'nullable|array',
        ]);
    
        // Cập nhật thông tin sản phẩm (trừ ảnh, size, màu)
        $data = $request->except(['images', 'variants']);
        
        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            $data['image'] = implode(',', $imagePaths);
        }
    
        $product->update($data);
    
        // Cập nhật biến thể sản phẩm
        $product->variants()->delete(); // Xóa các biến thể cũ
    
        if ($request->has('variants')) {
            foreach ($request->variants as $sizeId => $colors) {
                foreach ($colors as $colorId => $variant) {
                    if (isset($variant['selected'])) {
                        $product->variants()->create([
                            'size_id' => $sizeId,
                            'color_id' => $colorId,
                            'stock_quantity' => $variant['stock_quantity'] ?? 0,
                            'price' => $request->price, // Áp dụng chung giá
                        ]);
                    }
                }
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }
    
    public function destroy(Product $product) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->colors()->detach();
        $product->sizes()->detach();
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa!');
    }
}
