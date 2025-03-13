<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promotion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promotions,code|max:50',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'min_purchase_amount' => 'nullable|numeric|min:0',
        ]);

        Promotion::create($request->all());
        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully!');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->promotion_id . ',promotion_id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'min_purchase_amount' => 'nullable|numeric|min:0',
        ]);

        $promotion->update($request->all());
        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully!');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully!');
    }
}
