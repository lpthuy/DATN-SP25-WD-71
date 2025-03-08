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
        $products = Product::with(['category', 'variants.size', 'variants.color'])->paginate(10);
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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'required|array|min:1', // Ít nhất một biến thể
        ]);
    
        // Lưu thông tin sản phẩm
        $data = $request->except(['images', 'variants']);
        $imagePaths = [];
    
        if ($request->hasFile('images')) {
            $imagePaths = array_map(fn($image) => $image->store('products', 'public'), $request->file('images'));
            $data['image'] = implode(',', $imagePaths);
        }
    
        // Tạo sản phẩm trước để lấy ID
        $product = Product::create($data);
    
        // Lưu từng biến thể với giá riêng
        $variants = [];
    
        foreach ($request->variants as $sizeId => $colors) {
            foreach ($colors as $colorId => $variant) {
                if (!isset($variant['selected']) || !isset($variant['price'])) continue;
    
                $variants[] = [
                    'product_id' => $product->id,
                    'size_id' => $sizeId,
                    'color_id' => $colorId,
                    'stock_quantity' => (int) ($variant['stock_quantity'] ?? 0),
                    'price' => (float) $variant['price'],
                    'discount_price' => isset($variant['discount_price']) ? (float) $variant['discount_price'] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
    
        if (!empty($variants)) {
            ProductVariant::insert($variants);
        }
    
        return redirect()->route('products.index')->with('success', 'Sản phẩm và biến thể đã được tạo thành công!');
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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'nullable|array',
        ]);
    
        // Cập nhật thông tin sản phẩm (trừ ảnh)
        $data = $request->except(['images', 'variants']);
    
        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            $data['image'] = implode(',', $imagePaths);
        } else {
            // Giữ nguyên ảnh cũ nếu không có ảnh mới
            $data['image'] = $product->image;
        }
    
        $product->update($data);
    
        // ✅ Cập nhật biến thể mà không xóa toàn bộ dữ liệu cũ
        foreach ($request->variants as $sizeId => $colors) {
            foreach ($colors as $colorId => $variant) {
                if (isset($variant['selected']) && isset($variant['price'])) {
                    ProductVariant::updateOrCreate(
                        ['product_id' => $product->id, 'size_id' => $sizeId, 'color_id' => $colorId],
                        [
                            'stock_quantity' => (int) ($variant['stock_quantity'] ?? 0),
                            'price' => (float) $variant['price'],
                            'discount_price' => isset($variant['discount_price']) ? (float) $variant['discount_price'] : null,
                        ]
                    );
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
