<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Collection;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class CollectionController extends Controller
{
    //Authenticated need login index
    public function index()
    {
        return Inertia::render('Admin/Admin_Collection');
    }

    public function admin_index()
    {
        $collections = Collection::all();

        return response()->json($collections);
    }

    // Does not need login index
    public function index_admin()
    {
        $collections = Collection::where('activeStatus', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($collections);
    }

    //View Add Collection
    public function add_collection(Request $request){
        return Inertia::render('Admin/Add_Collection');
    }

    // View Edit Collection
    public function edit_collection(Request $request, $id){
        // Retrieve the collection based on the provided $id
        $collection = Collection::findOrFail($id);

        // Access the related categories using the relationship method
        $categories = $collection->categories;

        // Access the products associated with the collection ID
        $products = Product::where('collection_id', $id)->get();

        // Access thumbnails based on the conditions (collection_id, category_id, status = 3)
        $thumbnails = Product::where('collection_id', $id)
                            ->where('status', 3)
                            ->get();
        // dd($thumbnails);
        // Pass the $collection, $categories, and $thumbnails data to the Inertia view
        return Inertia::render('Admin/Edit_Collection', [
            'collection' => $collection,
            'categories' => $categories,
            'products' => $products,
            'thumbnails' => $thumbnails,
        ]);
    }

    // Get the Categories for collection
    public function getCategories($id)
    {
        $collection = Collection::with('categories')->findOrFail($id);

        $categories = $collection->categories;

        return response()->json($categories);
    }

    // Store/Add Collection
    public function store(Request $request)
    {
        try {
            // Validate form data
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation for image uploads
                'categories' => 'array'
            ]);

            $image = $request->file('image');
            $imagePath = $image ? $image->storeAs('images/collection/', uniqid() . '_' . $image->getClientOriginalName(), 'public') : null;

            // Create the collection with the provided data
            $collection = Collection::create([
                'name' => $request->name,
                // 'categories' => $request->categories, // 'categories' will be null if not provided
                'image' => $imagePath,
                'activeStatus' => 1,
            ]);
            // Sync categories
            $collection->categories()->sync($request->input('categories', []));

            return redirect()->route('collection')->with(['message' => 'Collection added successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //UPDATE THE COLLECTION
    public function update(Request $request, $id)
    {
        // dd($request->input('categories', []));
        // Find the collection by ID
        $collection = Collection::findOrFail($id);

        // Update collection properties
        $collection->name = $request->input('name');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store the new image and update the image path
            $imagePath = $request->file('image')->store('collections', 'public');
            $collection->image = $imagePath;
        }

        // $collection->categories()->sync($request->input('categories', []));
        // Update categories based on the request data
        $collection->categories = $request->input('categories', []);

        $collection->save();

        // Redirect or respond as needed
        return redirect()->route('admin_collection')->with('success', 'Collection updated successfully');
    }

    // VIEW COLELCTION
    public function view($id)
    {
        // Retrieve the collection based on the provided $id
        $collection = Collection::findOrFail($id);

        // Access the related categories using the relationship method
        $categories = $collection->categories;

        // Access the products associated with the collection ID
        $products = Product::where('collection_id', $id)->get();

        // Access thumbnails based on the conditions (collection_id, category_id, status = 3)
        $thumbnails = Product::where('collection_id', $id)
                            ->where('status', 3)
                            ->get();
        // dd($thumbnails);
        // Pass the $collection, $categories, and $thumbnails data to the Inertia view
        return Inertia::render('Admin/View_Collection', [
            'collection' => $collection,
            'categories' => $categories,
            'products' => $products,
            'thumbnails' => $thumbnails,
        ]);
    }

    // VIEW CATEGORIES IN COLLECTION(Front)
    public function view_cat($id)
    {
        // Retrieve the collection based on the provided $id
        $collection = Collection::findOrFail($id);

        // Access the related categories using the relationship method
        $categories = $collection->categories;

        // Access the products associated with the collection ID
        $products = Product::where('collection_id', $id)->get();

        // Access thumbnails based on the conditions (collection_id, category_id, status = 3)
        $thumbnails = Product::where('collection_id', $id)
                            ->where('status', 3)
                            ->get();
        // dd($thumbnails);
        // Pass the $collection, $categories, and $thumbnails data to the Inertia view
        return Inertia::render('ViewCollection', [
            'collection' => $collection,
            'categories' => $categories,
            'products' => $products,
            'thumbnails' => $thumbnails,
        ]);
    }

    // VIEW CATEGORIES IN COLLECTION(Front)
    public function showCategoryProducts($collectionName, $categoryName)
    {
        $collection = Collection::where('name', $collectionName)->firstOrFail();
        $category = Category::where('name', $categoryName)->firstOrFail();

        $products = Product::where('collection_id', $collection->id)
            ->where('category_id', $category->id)
            ->where('status', 1)
            ->get();


        return Inertia::render('CategoryProduct', [
            'collection' => $collection,
            'category' => $category,
            'products' => $products,
        ]);
    }

    // Show Product details (Front)
    public function showProduct($collectionName, $categoryName, $productName)
    {
        $collection = Collection::where('name', $collectionName)->firstOrFail();
        $category = Category::where('name', $categoryName)->firstOrFail();

        $product = Product::where('collection_id', $collection->id)
            ->where('category_id', $category->id)
            ->where('item_reference', $productName)
            ->firstOrFail(); // Use firstOrFail to retrieve a single instance

        return Inertia::render('ViewProduct', [
            'collection' => $collection,
            'category' => $category,
            'product' => $product,
        ]);
    }

    // Hide COllection
    public function deactivate($collectionId)
    {
        // Find the collection by ID
        $collection = Collection::findOrFail($collectionId);

        // Update the active status to 0
        $collection->update(['activeStatus' => 0]);

        // Optionally, return a response indicating success
        return response()->json(['message' => 'Collection deactivated successfully']);
    }

    // Show deactivated collection
    public function activate($collectionId)
    {
        // Find the collection by ID
        $collection = Collection::findOrFail($collectionId);

        // Update the active status to 1
        $collection->update(['activeStatus' => 1]);

        // Optionally, return a response indicating success
        return response()->json(['message' => 'Collection deactivated successfully']);
    }


}
