<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderStatisticController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'monthly');
        $year = $request->input('year', Carbon::now()->year);

        $stats = $this->getOrderStatistics($filter, $year);

        return view('admin.orders.statistics', compact('stats', 'filter', 'year'));
    }

    private function getOrderStatistics($filter, $year)
    {
        $query = Order::selectRaw('
            DATE_FORMAT(order_date, ?) as period,
            COUNT(*) as order_count,
            SUM(total_amount) as total_revenue,
            SUM(shipping_cost) as total_shipping_cost
        ', [$this->getDateFormat($filter)])
            ->whereYear('order_date', $year)
            ->whereIn('status', ['delivered', 'shipped'])
            ->groupBy('period');

        return $query->get();
    }

    private function getDateFormat($filter)
    {
        switch ($filter) {
            case 'daily':
                return '%Y-%m-%d';
            case 'monthly':
                return '%Y-%m';
            case 'yearly':
                return '%Y';
            default:
                return '%Y-%m';
        }
    }
}
