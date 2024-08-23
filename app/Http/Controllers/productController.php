<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
class productController extends Controller
{
    

    public function getProduct(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric',
            'product_stocks' => 'required|numeric',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required'
        ]);

        // Handle the uploaded image
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = $image->getClientOriginalName(); // Use the original name
            $image->move(public_path('assets/img'), $imageName);
            $validatedData['product_image'] = $imageName; // Store the original image name
        } else {
            $validatedData['product_image'] = null; // Handle the case where there's no image
        }

        // Save the product
        Product::create($validatedData);

        return redirect()->back()->with('success', 'Product uploaded successfully.');
    }

    


    // update inventory
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_stocks = $request->input('product_stocks');
        
        // ayusin 
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = $image->getClientOriginalName(); // Use the original name
            $image->move(public_path('assets/img'), $imageName);
            $product['product_image'] = $imageName; // Store the original image name
        }

        $product->save();
        return redirect()->route('admins.inventory')->with('success', 'Product updated successfully.');
    }

    // delete inventory
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admins.inventory')->with('success', 'Product deleted successfully.');
    }

    // search product
    public function search(Request $request)
    {
        $query = Product::query();
    
        if ($request->has('search')) {
            $keyword = e($request->input('search'));
            $firstLetter = substr($keyword, 0, 1);  // Get the first letter of the keyword
            $query->where('product_name', 'LIKE', "$firstLetter%");
        }
    
        $products = $query->with('category')->get();
        $categories = Category::withCount('products')->get();
    
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            $cartItems = $cart ? $cart->items : collect();
        } else {
            $cartItems = collect();
        }
    
        $error = $products->isEmpty() ? 'No products found for "' . $request->input('search') . '"' : null;
    
        return view('home.index', compact(
            'products', 
            'categories',
            'cartItems',
            'error'
        ));
    }
    

   
}
