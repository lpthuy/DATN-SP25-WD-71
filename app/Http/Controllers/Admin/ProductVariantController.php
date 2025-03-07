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
    public function index(Request $request)
    {
        $productId = $request->query('product'); // Lấy product_id từ URL
        $product = Product::find($productId); // Tìm sản phẩm từ database
    
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm không tồn tại.');
        }
    
        $variants = ProductVariant::with(['size', 'color'])
            ->where('product_id', $productId)
            ->get()
            ->groupBy('color_id');
    
        $productImages = ProductImage::where('product_id', $productId)->get();
    
        return view('admin.product_variants.index', compact('product', 'variants', 'productImages'));
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

        $product = Product::findOrFail($request->product_id);
        $price = $product->price;

        foreach ($request->variants as $size_id => $colors) {
            foreach ($colors as $color_id => $variant) {
                if (!empty($variant['selected']) && !empty($variant['stock_quantity'])) {
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $request->product_id,
                            'size_id' => $size_id,
                            'color_id' => $color_id,
                        ],
                        [
                            'stock_quantity' => $variant['stock_quantity'],
                            'price' => $variant['price'] ?? $price,
                            'discount_price' => $variant['discount_price'] ?? 0,
                        ]
                    );
                }
            }
        }

        return redirect()->route('products_variants.index')->with('success', 'Biến thể sản phẩm đã được thêm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa biến thể.
     */
    public function edit($id)
    {
        $products_variant = ProductVariant::findOrFail($id);
        $products = Product::with('images')->get();
        $sizes = Size::all();
        $colors = Color::all();

        $selected_variants = ProductVariant::where('product_id', $products_variant->product_id)->get();

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
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variants' => 'required|array',
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->price;

        foreach ($request->variants as $size_id => $colors) {
            foreach ($colors as $color_id => $variant) {
                if (!empty($variant['selected']) && !empty($variant['stock_quantity'])) {
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $request->product_id,
                            'size_id' => $size_id,
                            'color_id' => $color_id,
                        ],
                        [
                            'stock_quantity' => $variant['stock_quantity'],
                            'price' => $variant['price'] ?? $price,
                            'discount_price' => $variant['discount_price'] ?? 0,
                        ]
                    );
                } else {
                    // Nếu bỏ chọn thì xóa biến thể đó
                    ProductVariant::where([
                        'product_id' => $request->product_id,
                        'size_id' => $size_id,
                        'color_id' => $color_id,
                    ])->delete();
                }
            }
        }

        return redirect()->route('products_variants.index')->with('success', 'Biến thể sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Xóa biến thể sản phẩm hoặc theo kích thước cụ thể.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $products_variant = ProductVariant::findOrFail($id);

            if ($request->has('size_ids')) {
                $request->validate([
                    'size_ids' => 'required|array',
                    'size_ids.*' => 'exists:sizes,id',
                ]);

                ProductVariant::where('product_id', $products_variant->product_id)
                    ->whereIn('size_id', $request->size_ids)
                    ->where('color_id', $products_variant->color_id)
                    ->delete();

                return redirect()->route('products_variants.index')
                    ->with('success', 'Kích thước được chọn đã được xóa thành công!');
            }

            $products_variant->delete();

            return redirect()->route('products_variants.index')
                ->with('success', 'Biến thể sản phẩm đã bị xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('products_variants.index')
                ->with('error', 'Lỗi khi xóa biến thể: ' . $e->getMessage());
        }
    }
}
