@extends('admins.layout')

@section('content')
<div class="main-wrapper">
    <main class="container section">
        <h1>Edit Product</h1>
        <form action="{{ route('admins.inventory.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
            </div>
            <div class="form-group">
                <label for="product_price">Price</label>
                <input type="text" class="form-control" id="product_price" name="product_price" value="{{ $product->product_price }}" required>
            </div>
            <div class="form-group">
                <label for="product_stocks">Stocks</label>
                <input type="number" class="form-control" id="product_stocks" name="product_stocks" value="{{ $product->product_stocks }}" required>
            </div>
            <div class="form-group">
                <label for="product_image">Product Image</label>
                <input type="file" class="form-control-file" id="product_image" name="product_image">
                @if($product->product_image)
                    <img src="{{ asset($product->product_image) }}" alt="Product Image" width="100">
                @endif
            </div>
            <button type="submit" class="btn clr-color1">Update</button>
        </form>
    </main>
</div>
@endsection
