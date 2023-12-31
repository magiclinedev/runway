<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use App\Models\Collection;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();

        return response()->json($categories);
    }

    public function add_category(Request $request){
        return Inertia::render('Admin/Add_Category');
    }

    public function store(Request $request){
        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category added successfully']);
    }

    public function view_thumbnail($categoryId, $collectionId){
        $thumbnail = Product::where('category_id', $categoryId)
                        ->where('collection_id', $collectionId)
                        ->first();
        // dd($thumbnail);
        return response()->json($thumbnail);
    }
}
