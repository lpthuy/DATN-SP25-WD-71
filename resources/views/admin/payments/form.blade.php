@extends('adminlte::page')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản lý Thanh toán</h2>
    
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.payments.create') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Số tiền (VND)</label>
                    <input type="number" name="amount" 
                           class="form-control" 
                           min="1000" 
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nội dung thanh toán</label>
                    <input type="text" name="order_info" 
                           class="form-control" 
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-credit-card me-2"></i>Thực hiện thanh toán
                </button>
            </form>
        </div>
    </div>
</div>
@endsection