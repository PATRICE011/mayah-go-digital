<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class productController extends Controller
{
    
    public function getProduct(Request $request){
        // Validate the request
        $storeData = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric',
            'product_stocks' => 'required|numeric',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store the image
        if ($request->hasFile('product_image')){
            $imageName = time().'.'.$request->product_image->extension();
            $request->product_image->move(public_path('img'), $imageName);
            $imagePath = 'img/' . $imageName;
            $storeData ['product_image'] = $imagePath ;
        }
       
        $save = Product::create($storeData);
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
            $imagePath = $request->file('product_image')->store('products', 'public');
            $product->product_image = $imagePath;
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
}
