@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Checkout</h1>
    <form action="{{ route('payment.process') }}" method="POST">

        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="form-group">
            <label>Product</label>
            <input type="text" class="form-control" value="{{ $product->name }}" readonly>
        </div>

        <div class="form-group">
            <label>Color</label>
            <select name="color" class="form-control" required>
                @foreach(json_decode($product->colors, true) as $color)
                <option value="{{ $color }}">{{ $color }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Size</label>
            <select name="size" class="form-control" required>
                @foreach(json_decode($product->sizes, true) as $size)
                <option value="{{ $size }}">{{ $size }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
        </div>

        <div class="form-group">
            <label>Payment Method</label>
            <div class="payment-methods">
                @foreach($paymentMethods as $method)
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="{{ $method->id }}" required>
                    <img src="{{ asset($method->image_path) }}" alt="{{ $method->method_name }}" style="width: 50px; height: 50px;">
                    <span>{{ $method->method_name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
    </form>
</div>
@endsection