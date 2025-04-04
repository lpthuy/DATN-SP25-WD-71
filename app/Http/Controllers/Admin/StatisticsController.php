<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Lọc theo trạng thái và thời gian
        $status = $request->get('status', '');
        $dateRange = $request->get('date_range', '');

        // Lọc đơn hàng theo trạng thái
        $query = Order::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                $startDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Lấy tổng đơn hàng trong ngày, tháng và năm
        $totalOrdersToday = $query->whereDate('created_at', Carbon::today())->count();
        $totalOrdersThisMonth = $query->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $totalOrdersThisYear = $query->whereYear('created_at', Carbon::now()->year)->count();

        // Thống kê đơn hàng theo trạng thái
        $statusStats = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // Top 3 sản phẩm bán chạy nhất
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(3)
            ->get();

        return view('admin.statistics.index', compact(
            'totalOrdersToday',
            'totalOrdersThisMonth',
            'totalOrdersThisYear',
            'statusStats',
            'topProducts'
        ));
    }
}
