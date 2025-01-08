<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class categoryController extends Controller
{
    //
    public function admincategories(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::query();

            // Apply search filter if there's a search term
            if ($request->has('search') && !empty($request->search)) {
                $query->where('category_name', 'like', '%' . $request->search . '%');
            }

            $categories = $query->paginate(5);

            return response()->json([
                'data' => $categories->items(), // Paginated category data
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ]);
        }

        return view("admins.admincategories");
    }


    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name|max:255',
            'category_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction(); // ✅ Start Transaction

        try {
            $imageName = null;

            // Handle Image Upload
            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/img/'), $imageName);
            }

            // ✅ Insert data using DB instead of Eloquent
            $categoryId = DB::table('categories')->insertGetId([
                'category_name' => $request->category_name,
                'category_image' => $imageName,
                'slug' => Str::slug($request->category_name),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit(); // ✅ Commit Transaction

            return response()->json([
                'success' => true,
                'message' => 'Category added successfully!',
                'category' => [
                    'id' => $categoryId,
                    'category_name' => $request->category_name,
                    'category_image' => $imageName,
                    'slug' => Str::slug($request->category_name)
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id); // Ensure the category exists
            $category->delete(); // Delete the category

            return response()->json([
                'success' => true,
                'message' => 'Category archived successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive category. Please try again.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $category = Category::findOrFail($id);

            $category->category_name = $request->input('category_name');

            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/img'), $imageName);
                $category->category_image = $imageName;
            }

            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category. Please try again.',
            ], 500);
        }
    }

    public function printCategories()
    {
        // Fetch categories from the database
        $categories = Category::all();

        // Pass categories to the Blade view
        return view('admins.export-category', compact('categories'));
    }
}
