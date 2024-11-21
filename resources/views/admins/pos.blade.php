@extends('admins.layout')
@section('title', 'POS')
@section('content')

<div class="pos-wrapper d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="container py-5 section">
    <!-- Search and Filters -->
    <div class="row">
      <div class="col-12 mb-4">
        <div class="d-flex justify-content-start align-items-center flex-wrap gap-3">
          <!-- Search Input -->
          <input type="text" class="form-control w-25 mb-2" placeholder="Search Here...">
          <form method="GET" action="{{ route('admins.pos') }}">
            <select name="category_id" id="category" class="form-select" onchange="this.form.submit()">
              <option value="" {{ empty($selectedCategoryId) ? 'selected' : '' }}>All Categories</option>
              @foreach ($categories as $category)
              <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                {{ $category->category_name }}
              </option>
              @endforeach
            </select>
          </form>

        </div>
      </div>
    </div>

    <!-- Main Content: Product Grid & Order Details -->
    <div class="row g-4">
      <!-- Product Grid -->
      <div class="col-12 col-lg-9">
        <div class="row g-4">
          @forelse ($products as $product)
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              @if ($product->is_flash_sale)
              <div class="flash-sale">Flash Sale</div>
              @endif
              <img src="{{ asset('assets/img/' . $product->product_image) }}"
                alt="{{ $product->product_name }}" class="img-fluid">
              <div class="product-details">
                <h5 class="product-name">{{ $product->product_name }}</h5>

                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₱{{ number_format($product->product_price, 2) }}</span>
                  @if ($product->original_price)
                  <span class="original-price">₱{{ number_format($product->original_price, 2) }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @empty
          <p class="text-center">No products available.</p>
          @endforelse
        </div>
      </div>

      <!-- Order Details -->
      <div class="col-12 col-lg-3">
        <div class="order-details-wrapper bg-light p-4 rounded">
          <div class="d-flex justify-content-center align-items-center mb-3">
            <img class="img-fluid" style="max-width: 250px; height: auto;"
              src="https://demo.shopperz.codezenbd.com/images/required/empty-cart.gif" alt="empty">
          </div>
          <h5 class="mb-4">Order Details</h5>
          <div class="d-flex justify-content-between mb-3">
            <span>SubTotal</span>
            <span>₱0.00</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span>Tax</span>
            <span>₱0.00</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span>Discount</span>
            <span>₱0.00</span>
          </div>
          <div class="d-flex justify-content-between">
            <strong>Total</strong>
            <strong>₱0.00</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection