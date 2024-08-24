<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    //
    public function getCategory(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255'
            
        ]);


        // Save the product
        Category::create($validatedData);

        return redirect()->back()->with('success', 'Product uploaded successfully.');
    }

    public function update(Request $request, $id){
        $category = Category::findOrFail($id);
        $category->category_name = $request->input('category_name');
        
        $category->save();
        return redirect()->route('admins.category')->with('success', 'Product updated successfully.');
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admins.category')->with('success', 'Product deleted successfully.');
    }

}
