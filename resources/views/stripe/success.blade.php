@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-4">Payment Confirmed!</h1>
                    <p class="lead mb-4">{{ $message }}</p>
                    
                    <div class="order-details mb-4">
                        <h4>Order Details</h4>
                        <p>Order ID: #{{ $order->id }}</p>
                        <p>Total Amount: €{{ number_format($order->total_amount, 2) }}</p>
                        <p>Status: {{ ucfirst($order->status) }}</p>
                        <p>Payment Method: {{ ucfirst($order->payment_method) }}</p>
                    </div>

                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 