<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function index_admin(Request $request){
        return Inertia::render('Admin/Admin_Products');
    }

    public function add_product(Request $request){
        return Inertia::render('Admin/Add_Product');
    }
    public function view_product($id){
        $product = Product::findOrFail($id);
        // dd($product);
        return Inertia::render('Admin/View_Product', [
            'product' => $product
        ]);
    }

    public function store(Request $request){

        // dd($request->all());
        // $validatedData = $request->validate([
        //     'item_reference' => 'required|string|max:255',
        //     'collection' => 'required|string',
        //     'categories' => 'required|string',
        //     'description' => 'nullable|string',
        // ]);
        // dd($request->all());
        $image = $request->file('images');
        $imagePath = $image ? $image->storeAs('images/products', uniqid() . '_' . $image->getClientOriginalName(), 'public') : null;

        // Create a new collection record using the form data
        $collection = Product::create([
            'item_reference' => $request->item_reference,
            'collection_id' => $request->collection_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'images' => $imagePath,
            'status' => 1,
        ]);

        // You can perform additional actions, such as saving images, if needed

        // Respond with a success message
        return response()->json(['message' => 'Product added successfully']);
    }

    public function store_thumbnail(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Find the existing product by collection_id and item_reference
            $existingProduct = Product::where('collection_id', $request->collection_id)
                                        ->where('category_id', $request->category_id)
                                        ->where('item_reference', 'thumbnail')
                                        ->first();

            if ($existingProduct) {
                // Update the existing product with the new category_id and collection_id
                $existingProduct->update([
                    'category_id' => $request->category_id,
                    'collection_id' => $request->collection_id,
                ]);

                // Handle updating the thumbnail image (if needed)
                if ($request->hasFile('image')) {
                    // Delete the existing product's image
                    Storage::disk('public')->delete($existingProduct->images);

                    // Upload and store the new image
                    $image = $request->file('image');
                    $imagePath = $image->storeAs('images/categories/thumbnail', uniqid() . '_' . $image->getClientOriginalName(), 'public');

                    // Update the product with the new image path
                    $existingProduct->update(['images' => $imagePath]);
                }
            } else {
                // Handle creating a new product if the existing one doesn't exist
                $image = $request->file('image');
                $imagePath = $image->storeAs('images/categories/thumbnail', uniqid() . '_' . $image->getClientOriginalName(), 'public');

                // Create a new product with the uploaded image as a thumbnail
                $product = Product::create([
                    'item_reference' => 'thumbnail',
                    'category_id' => $request->category_id,
                    'description' => 'thumbnail',
                    'collection_id' => $request->collection_id,
                    'images' => $imagePath,
                    'status' => 3,
                ]);
            }

            // Return a response or redirect as needed
            return response()->json(['message' => 'Collection added/updated successfully']);
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e);

            // Return a response for the error
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function edit_product(Request $request, $id){
        // Retrieve the product based on the provided $id
        $product = Product::findOrFail($id);

        return Inertia::render('Admin/Edit_Product', [
            'product' => $product,
        ]);
    }
    public function update(Request $request, $id){
        $product = Product::findOrFail($id);

        // dd($request->all());
        $product->item_reference = $request->input('item_reference');

        if ($request->hasFile('images')) {
            // Store the new image and update the image path
            $imagePath = $request->file('images')->store('products', 'public');
            $product->images = $imagePath;
        }

        $product->save();

        // Redirect or respond as needed
        return redirect()->route('admin_product')->with('success', 'product updated successfully');
    }

    public function deactivate($productId)
    {
        // Find the product by ID
        $product = Product::findOrFail($productId);

        // Update the active status to 0
        $product->update(['status' => 0]);

        // Optionally, return a response indicating success
        return response()->json(['message' => 'product deactivated successfully']);
    }

    public function activate($productId)
    {
        // Find the product by ID
        $product = Product::findOrFail($productId);

        // Update the active status to 1
        $product->update(['status' => 1]);

        // Optionally, return a response indicating success
        return response()->json(['message' => 'product deactivated successfully']);
    }
}
