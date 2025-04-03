<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use DB;
use Carbon\Carbon;

class RevenueStatisticsController extends Controller
{
    public function statistics(Request $request)
    {
        $filterType = $request->input('filter_type', 'day');
        $filterValue = $request->input('filter_value', Carbon::now()->format('Y-m-d'));

        $query = Order::selectRaw("
            DATE_FORMAT(created_at, ?) as time, 
            SUM((SELECT SUM(quantity * price) FROM order_items WHERE order_id = orders.id)) as revenue
        ", [$this->getDateFormat($filterType)])
        ->groupBy('time');

        if ($filterType == 'day') {
            $query->whereDate('created_at', $filterValue);
        } elseif ($filterType == 'month') {
            $query->whereMonth('created_at', Carbon::parse($filterValue)->month);
        } elseif ($filterType == 'year') {
            $query->whereYear('created_at', $filterValue);
        }

        $revenues = $query->get()->pluck('revenue', 'time');
        $totalRevenue = $revenues->sum();

        return view('admin.statistics.revenue', compact('revenues', 'totalRevenue', 'filterType', 'filterValue'));
    }

    private function getDateFormat($type)
    {
        return match ($type) {
            'day' => '%Y-%m-%d',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d',
        };
    }
}
