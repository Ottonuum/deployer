<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyFavoriteSubjectController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Only login routes
Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Blog routes - all require authentication
Route::middleware(['auth'])->group(function () {
    // Regular user blog routes
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy'])->name('blog.destroy');
    Route::post('/blog/{blog}/comments', [BlogController::class, 'storeComment'])->name('blog.comments.store');
});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/blog', [BlogController::class, 'admin'])->name('admin.blog');
    Route::get('/admin/comments', [CommentController::class, 'index'])->name('admin.comments');
    Route::put('/admin/comments/{comment}/approve', [CommentController::class, 'approve'])->name('admin.comments.approve');
    Route::delete('/admin/comments/{comment}', [CommentController::class, 'destroy'])->name('admin.comments.destroy');
});

// Map routes
Route::get('/map', [MapController::class, 'getMap'])->name('map');

// Marker API routes
Route::get('/api/markers', [MarkerController::class, 'index']);
Route::post('/api/markers', [MarkerController::class, 'store']);
Route::get('/api/markers/{marker}', [MarkerController::class, 'show']);
Route::put('/api/markers/{marker}', [MarkerController::class, 'update']);
Route::delete('/api/markers/{marker}', [MarkerController::class, 'destroy']);

// Weather routes
Route::get('/weather', function () {
    return view('weather.form');
})->name('weather.form');

Route::get('/weather/{city}', [WeatherController::class, 'getWeather'])->name('weather.show');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// Order routes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
});

// Favorite Subjects Routes
Route::get('/favorite-subjects', [MyFavoriteSubjectController::class, 'index'])->name('favorite-subjects.index');
Route::get('/favorite-subjects/create', [MyFavoriteSubjectController::class, 'create'])->name('favorite-subjects.create');
Route::post('/favorite-subjects', [MyFavoriteSubjectController::class, 'store'])->name('favorite-subjects.store');
Route::delete('/favorite-subjects/{subject}', [MyFavoriteSubjectController::class, 'destroy'])->name('favorite-subjects.destroy');
Route::put('/favorite-subjects/{subject}', [MyFavoriteSubjectController::class, 'update'])->name('favorite-subjects.update');
Route::get('/api-search', [MyFavoriteSubjectController::class, 'apiSearch'])->name('api.search');

// API Routes
Route::get('/api/favorite-subjects', [MyFavoriteSubjectController::class, 'apiIndex']);

// Stripe Routes
Route::post('/stripe/checkout', [StripeController::class, 'createCheckoutSession'])->name('stripe.checkout');
Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
