<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CollectionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// NEED AUTH/DASHBOARD
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin_collection', [CollectionController::class, 'index'])->name('admin_collection');
    Route::get('/admin_products', [ProductController::class, 'index_admin'])->name('admin_product');

    Route::get('/collection_admin', [CollectionController::class, 'admin_index'])->name('collection.admin');;


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/add_product', [ProductController::class, 'add_product'])->name('add.product');
    Route::post('/collection-product', [ProductController::class, 'store'])->name('store.product');
    Route::post('/category-thumbnail-product', [ProductController::class, 'store_thumbnail'])->name('thumbnail.product');
    Route::get('/view_product/{id}', [ProductController::class, 'view_product'])->name('view.product');
    Route::get('/edit_product/{id}', [ProductController::class, 'edit_product'])->name('edit.product');
    Route::post('/update_product/{id}', [ProductController::class, 'update'])->name('update.product');

    Route::post('/deactivate_product/{productId}', [ProductController::class, 'deactivate']);
    Route::post('/activate_product/{productId}', [ProductController::class, 'activate']);


    Route::get('/add_category', [CategoryController::class, 'add_category'])->name('add.category');
    Route::post('/store_category', [CategoryController::class, 'store'])->name('store.category');
    // Route::get('/view_thumbnail', [CategoryController::class, 'view_thumbnail'])->name('thumbnail.category');

    Route::get('/add_collection', [CollectionController::class, 'add_collection'])->name('add.collection');
    Route::post('/upload_collection', [CollectionController::class, 'upload'])->name('upload.collection');
    Route::post('/store_collection', [CollectionController::class, 'store'])->name('store.collection');
    Route::get('/edit_collection/{id}', [CollectionController::class, 'edit_collection'])->name('edit.collection');
    Route::post('/update_collection/{id}', [CollectionController::class, 'update'])->name('update.collection');
    Route::get('/view_collection/{id}', [CollectionController::class, 'view'])->name('view.collection_admin');
    Route::get('/collection/{id}/categories', [CollectionController::class, 'getCategories']);

    Route::post('/deactivate_collection/{collectionId}', [CollectionController::class, 'deactivate']);
    Route::post('/activate_collection/{collectionId}', [CollectionController::class, 'activate']);


});

// Routes that don't require authentication
Route::get('/collection', [CollectionController::class, 'index_admin'])->name('admin.collection');;
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);


Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('About', function () {
    return Inertia::render('About');
})->name('about');

Route::get('Collection', function () {
    return Inertia::render('Collection');
})->name('collection');

Route::get('login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

//VIEW COLLECTION's Categories
Route::get('/collection_view/{id}', [CollectionController::class, 'view_cat'])->name('view.collection');
Route::get('/collection/{collectionName}/category/{categoryName}', [CollectionController::class, 'showCategoryProducts'])->name('category.products');

Route::get('/collection/{collectionName}/category/{categoryName}/product/{productName}', [CollectionController::class, 'showProduct'])->name('collection.products');





require __DIR__.'/auth.php';
