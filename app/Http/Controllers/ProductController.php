<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function store(Request $request){

        // dd($request->all());
        // $validatedData = $request->validate([
        //     'item_reference' => 'required|string|max:255',
        //     'collection' => 'required|string',
        //     'categories' => 'required|string',
        //     'description' => 'nullable|string',
        // ]);

        // Create a new collection record using the form data
        $collection = Product::create([
            'item_reference' => $request->item_reference,
            'collection_id' => $request->collection,
            'category_id' => $request->categories,
            'description' => $request->description,
            'images' => 1,
        ]);

        // You can perform additional actions, such as saving images, if needed

        // Respond with a success message
        return response()->json(['message' => 'Product added successfully']);
    }
}
