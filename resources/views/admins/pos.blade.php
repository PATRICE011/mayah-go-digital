@extends('admins.layout')
@section('title', 'POS')
@section('content')

<div class="pos-wrapper d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="container py-5 section">
    <div class="row">
      <!-- Search and Filters -->
      <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <input type="text" class="form-control w-25 mb-2" placeholder="Search Here...">
          <select class="form-select w-25 mb-2">
            <option selected>Select Category</option>
            <option value="1">Clothing</option>
            <option value="2">Electronics</option>
            <option value="3">Accessories</option>
          </select>
          <select class="form-select w-25 mb-2">
            <option selected>Select Brand</option>
            <option value="1">Brand A</option>
            <option value="2">Brand B</option>
            <option value="3">Brand C</option>
          </select>
          <select class="form-select w-25 mb-2">
            <option selected>Select Customer</option>
            <option value="1">John Doe</option>
            <option value="2">Jane Smith</option>
            <option value="3">Will Smith</option>
          </select>
        </div>
      </div>

      <!-- Product Grid -->
      <div class="col-9">
        <div class="row g-4">
          <!-- Product Card -->
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card position-relative">
              <div class="flash-sale">Flash Sale</div>
              <img src="https://via.placeholder.com/300x300" alt="Product Image">
              <div class="product-details">
                <h5 class="product-name">Classic Sweatshirt</h5>
                <p class="rating mb-2">★★★★☆</p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <span class="product-price">₹80.00</span>
                  <span class="original-price">₹100.00</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Duplicate this block for more products -->
        </div>
      </div>

      <!-- Order Details -->
      <div class="col-3">
        <div class="bg-light p-4 rounded">
        <div class="d-flex justify-content-center align-items-center">
            <img class="img-fluid" style="max-width: 250px; height: auto;" src="https://demo.shopperz.codezenbd.com/images/required/empty-cart.gif" alt="empty">
        </div>

          <h5 class="mb-4">Order Details</h5>
          <div class="d-flex justify-content-between mb-3">
            <span>SubTotal</span>
            <span>₹0.00</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span>Tax</span>
            <span>₹0.00</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span>Discount</span>
            <span>₹0.00</span>
          </div>
          <div class="d-flex justify-content-between">
            <strong>Total</strong>
            <strong>₹0.00</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection