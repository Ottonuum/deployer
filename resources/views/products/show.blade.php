@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->name }}">
            @endif
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="lead">${{ number_format($product->price, 2) }}</p>
            <p>{{ $product->description }}</p>
            <p><strong>Stock: {{ $product->stock_quantity }}</strong></p>
            @auth
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Add to Cart</button>
                </form>
            @else
                <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to add items to your cart.</p>
            @endauth
        </div>
    </div>
</div>
@endsection