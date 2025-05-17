<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;


// Define the login route with a name
Route::get('/', function(){
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('login');
})->name('login');
//login fucntionality
Route::post('/login',function(Request $request){
   $request->validate([
    "name"=>'required',
    "email"=>'required',
    "password"=>'required'
   ]);
   $user = User::where('name',$request->name)
     ->where('email',$request->email)
     ->first();
     if($user&&Hash::check($request->password, $user->password)){
        Auth::login($user);
        return redirect('/dashboard');
     }
     return redirect()->route('login')
     ->withErrors(['login'=>'invalid credentials']);

});


Route::get('/register', function(){
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('register');
});

//register fucntionality
Route::post('/register',function(Request $request){
    $request->validate([
        "name"=>'required',
        "email"=>'required|email|unique:users',
        "password"=>'required|min:6'
    ]);

    $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
    ]);

    Auth::login($user);
    return redirect('/dashboard');
});
// Protected routes
Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', function(){
        $userId = Auth::id();

        // Get total products count for this user
        $totalProducts = Product::where('user_id', $userId)->count();

        // Get total stock in quantity for this user
        $totalStockIn = StockIn::whereHas('product', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('quantity');

        // Get total stock out quantity for this user
        $totalStockOut = StockOut::whereHas('product', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('quantity');

        // Get current stock level
        $currentStock = $totalStockIn - $totalStockOut;

        // Get recent stock activities (both in and out) for this user
        $recentStockIns = StockIn::with('product')
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function($stockIn) {
                return [
                    'product_code' => $stockIn->product->product_code,
                    'product_name' => $stockIn->product->product_name,
                    'type' => 'IN',
                    'quantity' => $stockIn->quantity,
                    'date' => $stockIn->created_at->format('Y-m-d H:i'),
                    'created_at' => $stockIn->created_at
                ];
            });

        $recentStockOuts = StockOut::with('product')
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function($stockOut) {
                return [
                    'product_code' => $stockOut->product->product_code,
                    'product_name' => $stockOut->product->product_name,
                    'type' => 'OUT',
                    'quantity' => $stockOut->quantity,
                    'date' => $stockOut->created_at->format('Y-m-d H:i'),
                    'created_at' => $stockOut->created_at
                ];
            });

        $recentActivities = $recentStockIns->concat($recentStockOuts)->sortByDesc('created_at')->take(5);

        // Get products with low stock (less than 5 items) for this user
        $lowStockProducts = Product::where('user_id', $userId)
            ->withCount(['stockIns as total_in' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['stockOuts as total_out' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get()
            ->map(function($product) {
                $product->current_stock = $product->total_in - $product->total_out;
                return $product;
            })
            ->filter(function($product) {
                return $product->current_stock < 5 && $product->current_stock >= 0;
            })
            ->take(5);

        return view('dashboard', compact(
            'totalProducts',
            'totalStockIn',
            'totalStockOut',
            'currentStock',
            'recentActivities',
            'lowStockProducts'
        ));
    });

    Route::get('/products', function(){
        $userId = Auth::id();

        $products = Product::where('user_id', $userId)
            ->withCount(['stockIns as total_in' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['stockOuts as total_out' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get()
            ->each(function($product) {
                $product->total_quantity = $product->total_in - $product->total_out;
            });

        return view('products', compact('products'));
    });

    // Add product functionality
    Route::post('/products', function(Request $request){
        $request->validate([
            'product_name' => 'required',
            'product_code' => 'required|unique:products'
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'user_id'=>Auth::id()
        ]);

        return redirect('/products')->with('success', 'Product added successfully');
    });

    // Delete product functionality
    Route::delete('/products/{product}', function(Product $product){
        // Check if the product belongs to the authenticated user
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $product->delete();
        return redirect('/products')->with('success', 'Product deleted successfully');
    });
    Route::get('/stockin', function(){
        $userId = Auth::id();

        $products = Product::where('user_id', $userId)->get();
        $stockIns = StockIn::with('product')
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->get();

        return view('stockin', compact('products', 'stockIns'));
    });

    // Add stock in functionality
    Route::post('/stockin', function(Request $request){
        $userId = Auth::id();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Check if the product belongs to the authenticated user
        $product = Product::findOrFail($request->product_id);
        if ($product->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        StockIn::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        return redirect('/stockin')->with('success', 'Stock added successfully');
    });
    Route::get('/stockout', function(){
        $userId = Auth::id();

        // Get only products with available stock that belong to this user
        $products = Product::where('user_id', $userId)
            ->withCount(['stockIns as total_in' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['stockOuts as total_out' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get()
            ->filter(function($product) {
                $availableStock = $product->total_in - $product->total_out;
                return $availableStock > 0;
            });

        $stockOuts = StockOut::with('product')
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->get();

        return view('stockout', compact('products', 'stockOuts'));
    });

    // Add stock out functionality
    Route::post('/stockout', function(Request $request){
        $userId = Auth::id();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Check if the product belongs to the authenticated user
        $product = Product::where('user_id', $userId)
            ->withCount(['stockIns as total_in' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['stockOuts as total_out' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->findOrFail($request->product_id);

        $availableStock = $product->total_in - $product->total_out;

        if ($request->quantity > $availableStock) {
            return back()->withErrors(['quantity' => 'Insufficient stock. Available: ' . $availableStock]);
        }

        StockOut::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        return redirect('/stockout')->with('success', 'Stock out recorded successfully');
    });
    // Logout functionality
    Route::post('/logout', function() {
        Auth::logout();
        return redirect('/');
    });

    Route::get('/reports', function(Request $request){
        $userId = Auth::id();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $stockActivities = collect();
        $summary = null;

        if ($startDate && $endDate) {
            // Get stock in records within date range for this user
            $stockIns = StockIn::with('product')
                ->whereHas('product', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->get()
                ->map(function($stockIn) {
                    return [
                        'product_code' => $stockIn->product->product_code,
                        'product_name' => $stockIn->product->product_name,
                        'type' => 'IN',
                        'quantity' => $stockIn->quantity,
                        'date' => $stockIn->created_at->format('Y-m-d H:i'),
                        'created_at' => $stockIn->created_at
                    ];
                });

            // Get stock out records within date range for this user
            $stockOuts = StockOut::with('product')
                ->whereHas('product', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->get()
                ->map(function($stockOut) {
                    return [
                        'product_code' => $stockOut->product->product_code,
                        'product_name' => $stockOut->product->product_name,
                        'type' => 'OUT',
                        'quantity' => $stockOut->quantity,
                        'date' => $stockOut->created_at->format('Y-m-d H:i'),
                        'created_at' => $stockOut->created_at
                    ];
                });

            // Merge and sort by date
            $stockActivities = $stockIns->concat($stockOuts)->sortByDesc('created_at');

            // Calculate summary
            $totalStockIn = $stockIns->sum('quantity');
            $totalStockOut = $stockOuts->sum('quantity');

            // Get unique products in the report
            $productIds = $stockActivities->pluck('product_code')->unique();
            $productCount = $productIds->count();

            $summary = [
                'total_stock_in' => $totalStockIn,
                'total_stock_out' => $totalStockOut,
                'product_count' => $productCount
            ];
        }

        return view('reports', compact('stockActivities', 'startDate', 'endDate', 'summary'));
    });
});
