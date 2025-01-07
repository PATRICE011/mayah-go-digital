<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
}
