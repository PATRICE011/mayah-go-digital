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
        // 1) Build the query
        $query = Product::query();
    
        // 2) Apply filters if `search` is present
        if ($request->has('search')) {
            $keyword = $request->input('search');
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }
    
        $results = $query->get();
    
        // 3) If it's an AJAX request, return JSON with the partial
        if ($request->ajax()) {
            $html = view('home.partials.product_grid', [
                'products' => $results // partial expects `$products`
            ])->render();
    
            return response()->json([
                'html' => $html,
                'error' => $results->isEmpty() ? 'No products found.' : null
            ]);
        }
    
        // 4) Otherwise, return a full view for non-AJAX usage
        return view('home.shop', [
            'products' => $results
        ]);
    }
    

}
