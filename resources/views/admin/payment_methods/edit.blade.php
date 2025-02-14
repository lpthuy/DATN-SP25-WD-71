@extends('adminlte::page')

@section('content')
    <h1>Chỉnh sửa phương thức thanh toán</h1>

    <form action="{{ route('payment_methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="method_name">Tên phương thức</label>
            <input type="text" class="form-control" id="method_name" name="method_name" value="{{ $paymentMethod->method_name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description">{{ $paymentMethod->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            @if($paymentMethod->image_path)
                <img src="{{ asset('storage/' . $paymentMethod->image_path) }}" width="50" height="50" />
            @endif
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
@endsection
