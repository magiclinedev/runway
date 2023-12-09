<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Collection;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Admin_Collection');
    }

    public function index_admin()
    {
        $collection = Collection::orderBy('created_at', 'desc')->get();

        return response()->json($collection);
    }

    //View Add Collection
    public function add_collection(Request $request){
        return Inertia::render('Admin/Add_Collection');
    }

    // app/Http/Controllers/CollectionController.php

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
            ]);

            $image = $request->file('image');
            $imagePath = $image ? $image->storeAs('images/collection', uniqid() . '_' . $image->getClientOriginalName(), 'public') : null;

            // Create the collection with the provided data
            $collection = Collection::create([
                'name' => $request->name,
                'categories' => $request->categories, // 'categories' will be null if not provided
                'image' => $imagePath,
                'activeStatus' => 1,
            ]);

            return response()->json(['message' => 'Collection added successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function view($id)
    {
        // Retrieve the collection based on the provided $id
        $collection = Collection::findOrFail($id);

        // Pass the $collection data to the Inertia view
        return Inertia::render('Admin/View_Collection', [
            'collection' => $collection,
        ]);
    }

}
