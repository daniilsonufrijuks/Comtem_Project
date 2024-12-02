<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

//'routes' => [
//    'home' => route('home'),
//    'about' => route('about'),
//    'contacts' => route('contacts'),
//    'market' => route('market'),
//    'login' => route('login'),
//    'registration' => route('registration'),
//    // Add more routes here if needed
//],

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('/market', function () {
    return Inertia::render('Market');
})->name('market');

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::get('/registration', function () {
    return Inertia::render('Auth/Registration');
})->name('registration');

Route::get('/laptops', function () {
    return Inertia::render('Laptops');
})->name('laptops');

Route::get('/pcs', function () {
    return Inertia::render('Pcs');
})->name('pcs');

Route::get('/components', function () {
    return Inertia::render('Components');
})->name('components');

Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart');


//Route::get('/auction', function () {
//    return Inertia::render('Auction');
//})->name('auction');



// for dinamic product displaying
//Route::get('/product', function () {
//    return Inertia::render('Product');
//})->name('product');

//Route::get('/product/{id}', function ($id) {
//    return Inertia::render('Product', [
//        'productId' => $id,  // Pass the product ID to the frontend
//    ]);
//})->name('product');

Route::get('/product', function (Request $request) {
//    return Inertia::render('Product', [
//        'productId' => $request->query('id'), // Pass the query parameter to the frontend
//    ]);
    $productId = $request->query('id'); // Extract 'id' from query parameters
    if (!$productId) {
        abort(400, 'Product ID is required');
    }
    return Inertia::render('Product', [
        'productId' => $productId, // Pass it to the Vue page
    ]);
})->name('product');





Route::post('/contact', ContactController::class)->name('contact');


Route::get('/api/is-logged-in', function () {
    return response()->json([
        'isLoggedIn' => auth()->check(),
    ]);
});

// For web-based authentication (sessions)
Route::post('/logout', function () {
    auth()->logout();  // Log the user out
    session()->invalidate();  // Invalidate the session
    session()->regenerateToken();  // Regenerate CSRF token
    return response()->json(['message' => 'Logged out successfully']);
});




Route::get('/products/components', [ProductsController::class, 'getComponentsProducts']);
Route::get('/products/laptops', [ProductsController::class, 'getLaptopsProducts']);
Route::get('/products/pcs', [ProductsController::class, 'getPcsProducts']);



Route::get('/products/{id}', [ProductsController::class, 'show']);

//Route::get('/home', [PageController::class, 'home'])->name('home');
//Route::get('/about', [PageController::class, 'about'])->name('about');
//Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');
//Route::get('/market', [PageController::class, 'market'])->name('market');
//Route::post('/login', [PageController::class, 'login'])->name('login');
//Route::post('/registration', [PageController::class, 'registration'])->name('registration');

//Route::get('/login', [AuthenticatedSessionController::class, 'create'])
//    ->name('login');
//Route::post('/login', [AuthenticatedSessionController::class, 'store']);
//
//Route::middleware('auth')->get('/dashboard', function () {
//    return view('dashboard');
//});

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';
