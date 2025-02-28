<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductImage;

class ProductVariantController extends Controller
{
    /**
     * Hiển thị danh sách biến thể sản phẩm.
     */
    public function index()
    {
        $variants = ProductVariant::with('product', 'size', 'color')
            ->get()
            ->groupBy(function ($item) {
                return $item->product_id . '-' . $item->color_id;
            });

        // Lấy hình ảnh từ bảng products_images
        $productImages = ProductImage::all()->groupBy('product_id');

        return view('admin.product_variants.index', compact('variants', 'productImages'));
    }

    /**
     * Hiển thị form thêm biến thể sản phẩm.
     */
    public function create()
    {
        $products = Product::with('images')->get();
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.product_variants.create', compact('products', 'sizes', 'colors'));
    }


    /**
     * Lưu nhiều biến thể vào database.
     */
    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'variants' => 'required|array',
    ]);

    // Lấy giá từ sản phẩm
    $product = Product::findOrFail($request->product_id);
    $price = $product->price;

    // Lưu từng tổ hợp biến thể size + color + stock_quantity
    foreach ($request->variants as $size_id => $colors) {
        foreach ($colors as $color_id => $variant) {
            if (isset($variant['selected']) && isset($variant['stock_quantity']) && $variant['stock_quantity'] > 0) {
                ProductVariant::create([
                    'product_id' => $request->product_id,
                    'size_id' => $size_id,
                    'color_id' => $color_id,
                    'stock_quantity' => $variant['stock_quantity'],
                    'price' => $price, // Giá lấy từ sản phẩm
                ]);
            }
        }
    }

    return redirect()->route('products_variants.index')->with('success', 'Biến thể sản phẩm đã được thêm thành công!');
}


    /**
     * Hiển thị form chỉnh sửa biến thể.
     */
    public function edit(ProductVariant $products_variant)
{
    $products = Product::with('images')->get();
    $sizes = Size::all();
    $colors = Color::all();

    $selected_variants = ProductVariant::where('product_id', $products_variant->product_id)
                                        ->get();

    return view('admin.product_variants.edit', compact(
        'products_variant',
        'products',
        'sizes',
        'colors',
        'selected_variants'
    ));
}


    /**
     * Cập nhật biến thể trong database.
     */
    public function update(Request $request, ProductVariant $products_variant)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'variants' => 'required|array',
    ]);

    // Lấy giá từ sản phẩm
    $product = Product::findOrFail($request->product_id);
    $price = $product->price;

    // Xóa tất cả các biến thể cũ của sản phẩm này
    ProductVariant::where('product_id', $products_variant->product_id)->delete();

    // Thêm các biến thể mới
    foreach ($request->variants as $size_id => $colors) {
        foreach ($colors as $color_id => $variant) {
            if (isset($variant['selected']) && isset($variant['stock_quantity']) && $variant['stock_quantity'] > 0) {
                ProductVariant::create([
                    'product_id' => $request->product_id,
                    'size_id' => $size_id,
                    'color_id' => $color_id,
                    'stock_quantity' => $variant['stock_quantity'],
                    'price' => $price, // Giá lấy từ sản phẩm
                ]);
            }
        }
    }

    return redirect()->route('products_variants.index')->with('success', 'Biến thể sản phẩm đã được cập nhật thành công!');
}


    /**
     * Xóa toàn bộ biến thể của một sản phẩm.
     */
    /**
     * Xóa biến thể sản phẩm hoặc theo kích thước cụ thể.
     */
    public function destroy(Request $request, ProductVariant $products_variant)
    {
        try {
            // Kiểm tra nếu có chọn kích thước cụ thể để xóa
            if ($request->has('size_ids')) {
                $request->validate([
                    'size_ids' => 'required|array',
                    'size_ids.*' => 'exists:sizes,id',
                ]);

                // Xóa biến thể theo danh sách kích thước được chọn
                ProductVariant::where('product_id', $products_variant->product_id)
                    ->whereIn('size_id', $request->size_ids)
                    ->where('color_id', $products_variant->color_id)
                    ->delete();

                return redirect()->route('products_variants.index')
                    ->with('success', 'Kích thước được chọn đã được xóa thành công!');
            }

            // Nếu không chọn kích thước cụ thể, xóa toàn bộ biến thể theo ID
            $products_variant->delete();

            return redirect()->route('products_variants.index')
                ->with('success', 'Biến thể sản phẩm đã bị xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('products_variants.index')
                ->with('error', 'Lỗi khi xóa biến thể: ' . $e->getMessage());
        }
    }
}
