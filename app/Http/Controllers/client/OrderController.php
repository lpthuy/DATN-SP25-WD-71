<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Kiểm tra nếu người dùng chưa đăng nhập
            if (!Auth::check()) {
                return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập để mua hàng!'], 401);
            }

            // Kiểm tra phương thức request có đúng là POST không
            if (!$request->isMethod('post')) {
                return response()->json(['status' => 'error', 'message' => 'Phương thức không hợp lệ!'], 405);
            }

            // Kiểm tra CSRF Token
            if (!$request->hasHeader('X-CSRF-TOKEN')) {
                return response()->json(['status' => 'error', 'message' => 'Thiếu CSRF Token!'], 419);
            }

            // Kiểm tra dữ liệu đầu vào
            $validatedData = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'product_name' => 'required|string|max:255',
                'color' => 'required|string|max:50',
                'size' => 'required|string|max:10',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'payment_method' => 'required|string|max:50',
            ]);

            // Thêm user_id vào đơn hàng
            $validatedData['user_id'] = Auth::id();
            $validatedData['order_code'] = 'OD' . strtoupper(uniqid());

            // Lưu vào CSDL
            $order = Order::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Đơn hàng đã được lưu thành công!',
                'order_code' => $order->order_code,
                'redirect' => url('/orders/' . $order->id)
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi lưu đơn hàng: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi hệ thống! Kiểm tra logs để biết chi tiết.'
            ], 500);
        }
    }
    


    public function index() {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('client.pages.order', compact('user', 'orders'));
    }

    
    public function checkPaymentStatus(Request $request)
    {
        $productId = $request->query('product_id');

        if (!$productId) {
            return response()->json(['error' => 'Thiếu product_id'], 400);
        }

        // Kiểm tra xem có đơn hàng nào với product_id tương ứng hay không
        $order = Order::where('product_id', $productId)->first();

        if (!$order) {
            return response()->json(['error' => 'Không tìm thấy đơn hàng'], 404);
        }

        return response()->json([
            'payment_status' => $order->payment_status,
            'order_code' => $order->order_code
        ]);
    }
}

