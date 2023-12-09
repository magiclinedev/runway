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

Route::get('/collection', [CollectionController::class, 'index_admin']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin_collection', [CollectionController::class, 'index'])->name('admin_collection');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/collection-product', [ProductController::class, 'store'])->name('store.product');

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/add_category', [CategoryController::class, 'add_category'])->name('add.category');
    Route::post('/store_category', [CategoryController::class, 'store'])->name('store.category');

    Route::get('/add_collection', [CollectionController::class, 'add_collection'])->name('add.collection');
    Route::post('/upload_collection', [CollectionController::class, 'upload'])->name('upload.collection');
    Route::post('/store_collection', [CollectionController::class, 'store'])->name('store.collection');
    Route::get('/view_collection/{id}', [CollectionController::class, 'view'])->name('view.collection');
    Route::get('/collection/{id}/categories', [CollectionController::class, 'getCategories']);


});

// Routes that don't require authentication
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

require __DIR__.'/auth.php';
