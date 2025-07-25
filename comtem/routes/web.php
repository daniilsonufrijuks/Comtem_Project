<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
//use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouletteController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

Route::get('/contacts', function () {
    return Inertia::render('Contact');
})->name('contacts');

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

Route::get('/tutor', function () {
    return Inertia::render('Tutorials');
})->name('tutor');

Route::get('/auction', function () {
    return Inertia::render('Auction');
})->name('auction');

Route::get('/quiz', function () {
    return Inertia::render('Quiz');
})->name('quiz');

Route::get('/phones', function () {
    return Inertia::render('Phones');
})->name('phones');

Route::get('/peripherals', function () {
    return Inertia::render('Peripherals');
})->name('peripherals');

Route::get('/furniture', function () {
    return Inertia::render('Furniture');
})->name('furniture');

Route::get('/cables', function () {
    return Inertia::render('Cables');
})->name('cables');








//Route::get('/auction/add', function () {
//    return Inertia::render('AddAItem');
//})->name('addaitem');













// for dinamic product displaying
//Route::get('/product', function () {
//    return Inertia::render('Product');
//})->name('product');

//Route::get('/product/{id}', function ($id) {
//    return Inertia::render('Product', [
//        'productId' => $id,  // Pass the product ID to the frontend
//    ]);
//})->name('product');


// sends products id from ProductCardDB to Product pagge
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
        'user' => auth()->user(), // Include the authenticated user
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
Route::get('/products/phones', [ProductsController::class, 'getPhonesProducts']);

Route::get('/products/furniture', [ProductsController::class, 'getFurnitureProducts']);
Route::get('/products/cables', [ProductsController::class, 'getCablesProducts']);

Route::get('/products/peripherals', [ProductsController::class, 'getPeripheralsProducts']);
Route::get('/products/{id}', [ProductsController::class, 'show']);








// Check if user is logged in
//Route::get('/auth/user', function () {
//    return response()->json([
//        'loggedIn' => auth()->check(),
//        'id' => auth()->id(),
//    ]);
//});








// Proceed to checkout (with session-based authentication)
Route::post('/order', [OrderController::class, 'store'])->middleware('auth');







// to get user page about user
Route::get('/user', [UserController::class, 'userProfile'])->name('user');











// admin page and get/post
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/admin/orders', [AdminController::class, 'showOrders'])->name('admin.orders');
Route::get('/admin/ordersj', [AdminController::class, 'showjoinedOrders'])->name('admin.j.orders');
Route::get('/admin/products', [AdminController::class, 'showProducts'])->name('admin.products');
Route::post('/admin/products', [AdminController::class, 'storeProduct'])->name('admin.products.add');
Route::delete('/admin/products/{id}', [AdminController::class, 'destroyProduct']);
Route::delete('/admin/orders/{id}', [AdminController::class, 'destroyOrder']);
Route::put('/admin/products/{id}', [AdminController::class, 'update']);








// ai
//Route::post('/chatai', [ChatController::class, 'chat'])->name('chatai');
Route::post('chatai', [ChatController::class, 'chat'])->name('chatai');









// show auction items and send auction item id to inspect exactly one spicific product form auction list
Route::get('/auction/items', [AuctionController::class, 'getitems']);

// to send item id form auction to auctionitem when need to open description...
Route::get('/auctionitem', function (Request $request) {

    $productId = $request->query('id'); // Extract 'id' from query parameters
    if (!$productId) {
        abort(400, 'Product ID is required');
    }
    return Inertia::render('AuctionItem', [
        'productId' => $productId, // Pass it to the Vue page
    ]);
})->name('auctionitem');

// to get spicific item info from table auction by id
Route::get('/auctionitems/{id}', [AuctionController::class, 'show']);
// to add new item opens new page for adding
Route::get('/auction/add', [AuctionController::class, 'create']);
// to create new auction item
Route::post('/auction/store', [AuctionController::class, 'store'])->middleware('auth');
// for auction to place bids on auction items
Route::post('/place-bid/{item}', [BidController::class, 'placeBid'])->middleware('auth');
// delete auction items after end date
Route::delete('/delete-expired-auctions', [AuctionController::class, 'destroy']);






// for stripe after pressing proceed btn in cart
//Route::post('/checkout', [CheckoutController::class, 'checkout']);
Route::post('/stripe/checkout', [StripeController::class, 'create'])->name('stripe.checkout');



// to find products using search bar
//Route::get('/search', [ProductsController::class, 'search'])->name('products.search');
Route::get('/search', [ProductsController::class, 'search']);



// roulette
Route::get('/spin', [RouletteController::class, 'spin']);



// for comments
Route::get('/comments', [CommentController::class, 'index']);
Route::post('/comments', [CommentController::class, 'store']);



// for books and documentation
Route::get('/books', [BookController::class, 'index']);


//Route::post('/chatai', function (\Illuminate\Http\Request $request) {
//    $message = $request->input('message');
//
//    // Example using OpenAI's GPT
//    $client = new Client(['api_key' => env('OPENAI_API_KEY')]);
//
//    $response = $client->chat()->create([
//        'model' => 'gpt-4',
//        'messages' => [
//            ['role' => 'system', 'content' => 'You are an expert in PC components.'],
//            ['role' => 'user', 'content' => $message],
//        ],
//    ]);
//
//    return response()->json([
//        'reply' => $response['choices'][0]['message']['content'],
//    ]);
//});



//
//use GuzzleHttp\Client as GuzzleClient;
//
//Route::post('/chatai', function (\Illuminate\Http\Request $request) {
//    $message = $request->input('message');
//
//    try {
//        // Set up the necessary configuration for HttpTransporter
//        $config = [
//            'baseUri' => 'https://api.openai.com',  // API base URL
//            'headers' => [
//                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
//                'Content-Type' => 'application/json',
//            ],
//            'queryParams' => [],  // Optional query parameters
//            'streamHandler' => null,  // Set to null if not using streaming
//        ];
//
//        // Initialize HttpTransporter with the config
//        $transporter = new HttpTransporter(
//            $config['baseUri'],
//            $config['headers'],
//            $config['queryParams'],
//            $config['streamHandler']
//        );
//
//        // Create the OpenAI Client with the transporter
//        $client = new Client($transporter);
//
//        // Call the OpenAI chat method with the message
//        $response = $client->chat()->create([
//            'model' => 'gpt-4',
//            'messages' => [
//                ['role' => 'system', 'content' => 'You are an expert in PC components.'],
//                ['role' => 'user', 'content' => $message],
//            ],
//        ]);
//
//        return response()->json([
//            'reply' => $response['choices'][0]['message']['content'],
//        ]);
//    } catch (\Exception $e) {
//        \Log::error('OpenAI API Error:', ['error' => $e->getMessage()]);
//        return response()->json(['error' => 'Sorry, something went wrong. Please try again later.'], 500);
//    }
//});
//


//Route::post('/chatai', function (\Illuminate\Http\Request $request) {
//    $message = $request->input('message');
//
//    // Example using OpenAI's GPT
//    try {
//        $client = new \OpenAI\Client(['api_key' => env('OPENAI_API_KEY')]);
//
//        $response = $client->chat()->create([
//            'model' => 'gpt-4',
//            'messages' => [
//                ['role' => 'system', 'content' => 'You are an expert in PC components.'],
//                ['role' => 'user', 'content' => $message],
//            ],
//        ]);
//
//        Log::info('OpenAI Response:', ['response' => $response]);
//
//        return response()->json([
//            'reply' => $response['choices'][0]['message']['content'],
//        ]);
//    } catch (\Exception $e) {
//        Log::error('OpenAI API Error:', ['error' => $e->getMessage()]);
//        return response()->json(['error' => 'Sorry, something went wrong. Please try again later.'], 500);
//    }
//});

//Route::post('/chatai', [OpenAIController::class, 'generate']);

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
