@extends('admins.layout')
@section('content')
          
<div class="main-wrapper">
    <main class="container section">
  
        <!-- Filters and Table for Donations and Tithes List -->
        <div class="containers mt-4">
        <h1>Product Management</h1>
           
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
			<th>Name</th>
                        <th>Price (₱)</th>
                        <th>Stocks</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add your dynamic rows here -->
                    @foreach ($products as $product)
                    <tr>
                        <td></td>
                        <td><img src="{{ asset('assets/img/' . $product->product_image) }}" alt="Product Image" width="50"></td>
                        <!-- <td>Category Name</td> -->
                        <td>{{$product->product_name}}</td>
                        <td>{{$product->product_price}}</td>
                        <td>{{$product->product_stocks}}</td>
                        <td>
                           
                            <button type="button" class="btn clr-color2" data-toggle="modal" data-target="#donationModal-1">
                                Edit
                            </button>
                        </td>
                        <td>
                        <form action="{{ route('admins.inventory.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn clr-color1">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Button to trigger modal -->
            <button type="button" class="btn clr-color1" data-toggle="modal" data-target="#donationModal">
                Add Product
            </button>
        </div>

        <!-- Modal for Add Products -->
        <div class="modal fade" id="donationModal" tabindex="-1" role="dialog" aria-labelledby="donationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="donationModalLabel">Add Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admins.insertProduct')}}" method="post" class="login__form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="contributorName" name="product_name" placeholder="e.g., Bread Stix">
                            </div>
                            <div class="form-group">
                                <label for="product_pricet">Price</label>
                                <input type="text" class="form-control" id="amount" name="product_price" placeholder="e.g., 5">
                            </div>
                            <div class="form-group">
                                <label for="product_stocks">Stocks</label>
                                <input type="number" class="form-control" id="date" name="product_stocks" placeholder="e.g., 100">
                            </div>
			
                            <div class="form-group">
                                <label for="product_image">Product Image</label>
                                <input type="file" class="form-control-file" id="date" name="product_image">
                            </div>
                            <button type="submit" class="btn clr-color1">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Edit Products -->
        <div class="modal fade" id="donationModal-1" tabindex="-1" role="dialog" aria-labelledby="donationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="donationModalLabel">Edit Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @foreach ($products as $product )
                        <form action="{{route('admins.inventory.update', $product->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="contributorName" name="product_name" value="{{ $product->product_name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="product_pricet">Price</label>
                                <input type="text" class="form-control" id="amount" name="product_price" value="{{ $product->product_price }}" required>
                            </div>
                            <div class="form-group">
                                <label for="product_stocks">Stocks</label>
                                <input type="number" class="form-control" id="date" name="product_stocks" value="{{ $product->product_stocks }}" required>
                            </div>
			
                            <div class="form-group">
                                <label for="product_image">Product Image</label>
                                <input type="file" class="form-control-file" id="product_image" name="product_image">
                                @if($product->product_image)
                                    <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="Product Image" width="100">
                                @endif
                            </div>
                            <button type="submit" class="btn clr-color1">Update</button>
                        </form>
                        @endforeach
                       
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection