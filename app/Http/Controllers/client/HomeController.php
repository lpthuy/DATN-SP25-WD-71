<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

public function index() {
    $products = Product::all(); // Lấy tất cả sản phẩm từ database
    return view('client.pages.home', compact('products')); 
}


    public function about()
    {
        return view('client.pages.about');
    }

    public function product()
    {
        return view('client.pages.product');
    }

    public function productbycategory()
    {
        return view('client.pages.product-by-category');
    }

    public function productDetail($id)
{
    $product = Product::find($id);

    if ($product) {
        // Nếu cột `image` lưu nhiều ảnh dưới dạng chuỗi có dấu phẩy
        $images = explode(',', $product->image);

        // Lấy danh mục của sản phẩm
        $category = Category::find($product->category_id);

        // Lấy danh sách màu sắc của sản phẩm từ bảng `colors`
        $colors = Color::where('product_id', $id)->get();

        // Truyền dữ liệu sản phẩm, hình ảnh và danh mục sang view
        return view('client.pages.product-detail', compact('product', 'images', 'category', 'colors'));
    }

    return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại');
}

    
    



    public function post()
    {
        return view('client.pages.post');
    }

    public function contact()
    {
        return view('client.pages.contact');
    }

    public function search()
    {
        return view('client.pages.search');
    }

    public function wishlist()
    {
        return view('client.pages.wishlist');
    }

    

    public function checkOrder()
    {
        return view('client.pages.check-order');
    }
    public function chinhSachGiaoHang()
    {
        return view('client.pages.chinh-sach-giao-hang');
    }

    public function login()
    {
        return view('auth.client.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
            } elseif ($user->role === 'user') {
                return redirect()->route('profile')->with('success', 'Đăng nhập thành công!');
            }

            return redirect('/')->with('success', 'Đăng nhập thành công!');
        }

        return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng.');
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function register()
    {
        return view('auth.client.register');
    }

    /**
     * Xử lý đăng ký
     */
    public function doRegister(Request $request)
{
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'phone' => 'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone, // Lưu số điện thoại
        'password' => Hash::make($request->password),
        'role' => 'user'
    ]);

    Auth::login($user);

    return redirect()->route('profile')->with('success', 'Đăng ký thành công!');
}


    /**
     * Hiển thị trang đổi mật khẩu
     */
    

    /**
     * Xử lý đổi mật khẩu
     */
    public function doChangePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu cũ không chính xác.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile')->with('success', 'Mật khẩu đã được thay đổi.');
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất.');
    }

    public function profile()
{
    $user = Auth::user(); // Lấy thông tin người dùng đang đăng nhập
    return view('auth.client.profile', compact('user'));
}


public function editProfile()
{
    return view('auth.client.edit-profile', ['user' => Auth::user()]);
}

public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
    ]);

    $user = Auth::user();
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ]);

    return redirect()->route('profile')->with('success', 'Cập nhật thông tin thành công!');
}


    public function changePassword()
    {
        return view('auth.client.change-password');
    }

    public function order()
    {
        return view('client.pages.order');
    }

    public function address()
    {
        return view('client.pages.address');
    }
}