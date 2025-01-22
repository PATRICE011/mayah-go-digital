<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use App\Models\Audit;

use App\Exports\ProductsExport;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFileType;



class productController extends Controller
{

    public function adminproducts()
    {


        $categories = Category::all();

        if (request()->ajax()) {
            $query = request('query', ''); // Search term
            $categorySlug = request('category', ''); // Selected category slug
            $minPrice = request('minPrice', 0); // Minimum price filter
            $maxPrice = request('maxPrice', null); // Maximum price filter
            $status = request('status', ''); // Active/Inactive filter

            $products = Product::with('category')
                ->when($query, function ($queryBuilder) use ($query) {
                    $queryBuilder->where('product_name', 'like', "%$query%");
                })
                ->when($categorySlug, function ($queryBuilder) use ($categorySlug) {
                    $queryBuilder->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                        $categoryQuery->where('slug', $categorySlug); // Match category slug
                    });
                })
                ->when($minPrice, function ($queryBuilder) use ($minPrice) {
                    $queryBuilder->where('product_price', '>=', $minPrice);
                })
                ->when($maxPrice, function ($queryBuilder) use ($maxPrice) {
                    $queryBuilder->where('product_price', '<=', $maxPrice);
                })
                ->when($status, function ($queryBuilder) use ($status) {
                    $queryBuilder->where('product_stocks', $status === 'active' ? '>' : '=', 0);
                })
                ->paginate(10);

            return response()->json($products);
        }

        return view('admins.adminproducts', compact('categories'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'product_description' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'product_stocks' => 'required|integer|min:0', // Validate stocks
            'category_id' => 'required|exists:categories,id',
        ]);

        // Handle the image logic
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');

            // Define the destination path in the public directory
            $destinationPath = public_path('assets/img');

            // Use the original file name
            $imageName = $image->getClientOriginalName();

            // Check if the file already exists
            if (!file_exists($destinationPath . '/' . $imageName)) {
                // Move the file to the destination path if it does not exist
                $image->move($destinationPath, $imageName);
            }

            // Store the filename in the database
            $validatedData['product_image'] = $imageName;
        }

        // Generate a unique 8-digit product_id
        $validatedData['product_id'] = $this->generateUniqueProductId();

        try {
            $product = Product::create($validatedData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage(),
            ], 500);
        }


        // Log the audit
        Audit::create([
            'user_id' => Auth::id(),
            'action' => 'Added a Product',
            'model_type' => Product::class,
            'model_id' => $product->id,
            'changes' => $validatedData,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully!',
            'product' => $product
        ]);
    }

    /**
     * Generate a unique 8-digit product ID.
     *
     * @return string
     */
    private function generateUniqueProductId()
    {
        do {
            // Generate a random 8-digit number
            $productId = random_int(10000000, 99999999);
        } while (Product::where('product_id', $productId)->exists()); // Ensure uniqueness

        return (string) $productId;
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'product_price' => 'required|numeric|min:0',
                'product_stocks' => 'required|integer|min:0',
                'product_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Update product image if provided
            if ($request->hasFile('product_image')) {
                $imagePath = $request->file('product_image')->store('product_images', 'public');
                $validatedData['product_image'] = "/storage/" . $imagePath;
            }


            $oldValues = $product->getOriginal();
            $product->update($validatedData);
            // Log the audit
            Audit::create([
                'user_id' => Auth::id(),
                'action' => 'updated a Product',
                'model_type' => Product::class,
                'model_id' => $product->id,
                'old_values' => $oldValues,
                'changes' => $validatedData,
            ]);


            return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update product.', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            $oldValues = $product->toArray();
            $product->delete();

            // Log the audit
            Audit::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted a Product',
                'model_type' => Product::class,
                'model_id' => $id,
                'old_values' => $oldValues,
            ]);

            return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete product.'], 500);
        }
    }

    public function getAllCategories()
    {
        return response()->json(Category::all());
    }

    public function export()
    {
        // Download the export as an XLSX file
        $response = Excel::download(new ProductsExport, 'products.xlsx', ExcelFileType::XLSX);

        // Clean the output buffer (if necessary)
        ob_end_clean();

        return $response;
    }
}
