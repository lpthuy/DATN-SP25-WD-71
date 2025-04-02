<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // Hiển thị danh sách khuyến mãi
    public function index()
    {
        $promotions = Promotion::all();
        return view('admin.promotions.index', compact('promotions'));
    }

    // Hiển thị form tạo khuyến mãi mới
    public function create()
    {
        return view('admin.promotions.create');
    }

    // Lưu khuyến mãi mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:promotion,code',
            'discount_type'    => 'required|in:fixed,percentage',
            'discount_value'   => 'required|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'min_purchase_amount' => 'nullable|numeric|min:0',
        ]);

        Promotion::create($request->all());

        return redirect()->route('promotions.index')
            ->with('success', 'Khuyến mãi đã được tạo thành công.');
    }

    // Hiển thị chi tiết khuyến mãi hoặc form chỉnh sửa
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.edit', compact('promotion'));
    }

    // Cập nhật khuyến mãi
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'code'             => 'required|string|max:50|unique:promotion,code,' . $promotion->promotion_id . ',promotion_id',
            'discount_type'    => 'required|in:fixed,percentage',
            'discount_value'   => 'required|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'min_purchase_amount' => 'nullable|numeric|min:0',
        ]);

        $promotion->update($request->all());

        return redirect()->route('promotions.index')
            ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
    }

    // Xóa khuyến mãi
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', 'Khuyến mãi đã được xóa thành công.');
    }
}
