<?php

use App\Http\Controllers\admin\CartController as AdminCartController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\FavouriteController as AdminFavouriteController;
use App\Http\Controllers\admin\OrderController as AdminOrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\TeamMembersController;
use App\Http\Controllers\admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\product\CartController;
use App\Http\Controllers\product\CategoryController as ProductCategoryController;
use App\Http\Controllers\product\FavouriteController;
use App\Http\Controllers\product\OrderController;
use App\Http\Controllers\product\ProductController as ProductProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/login", [AuthController::class, "login"])->name("login");
Route::post("/register", [AuthController::class, "register"]);

Route::post("/logout", [AuthController::class, "logout"])->middleware('auth:sanctum');


//Other Routes
Route::get("/products", [ProductProductController::class, 'index']);
Route::get("/products/{id}", [ProductProductController::class, 'show']);
Route::get("/products/search/{keyword}", [ProductProductController::class, 'search']);

Route::get("/carts/{id}", [CartController::class, "show"]);

Route::get("/categories", [ProductCategoryController::class, "index"]);
Route::get("/categories/{id}", [ProductCategoryController::class, "show"]);


Route::get("/favourites/{id}", [FavouriteController::class, "show"]);

Route::get("/orders/{id}", [OrderController::class, "show"]);

Route::get("/transactions/{id}", [TransactionController::class, "show"]);




Route::group(["middleware" => ["auth:sanctum", "token"],], function () {
    Route::get("/carts", [CartController::class, "index"]);
    Route::post("/carts", [CartController::class, "store"]);
    Route::put("/carts/{id}", [CartController::class, "update"]);
    Route::delete("/carts/{id}", [CartController::class, "destroy"]);

    Route::get("/favourites", [FavouriteController::class, "index"]);
    Route::post("/favourites", [FavouriteController::class, "store"]);
    Route::delete("/favourites/{id}", [FavouriteController::class, "destroy"]);

    Route::get("/orders", [OrderController::class, "index"]);
    Route::post("/orders", [OrderController::class, "store"]);

    Route::get("/transactions", [TransactionController::class, "index"]);

    Route::get("/profile", [UserController::class, "index"]);
    Route::put("/profile", [UserController::class, "update"]);
});



// Admin Routes


//Product Routes
Route::get("/admin/products", [ProductController::class, 'index']);
Route::get("/admin/products/{id}", [ProductController::class, 'show']);
Route::get("/admin/products/search/{keyword}", [ProductController::class, 'search']);


//Cart Routes
Route::get("/admin/carts", [AdminCartController::class, "index"]);
Route::get("/admin/carts/{id}", [AdminCartController::class, "show"]);


//category
Route::get("/admin/categories", [CategoryController::class, "index"]);
Route::get("/admin/categories/{id}", [CategoryController::class, "show"]);

//favourites
Route::get("/admin/favourites/", [AdminFavouriteController::class, "index"]);
Route::get("/admin/favourites/{id}", [AdminFavouriteController::class, "show"]);

//order
Route::get("/admin/orders", [AdminOrderController::class, "index"]);
Route::get("/admin/orders/{id}", [AdminOrderController::class, "show"]);

//transaction
Route::get("/admin/transactions", [AdminTransactionController::class, "index"]);
Route::get("/admin/transactions/{id}", [AdminTransactionController::class, "show"]);

//Users



Route::group(["prefix" => "/admin", "middleware" => ["auth:sanctum", "token"],], function () {

    // Product Routes
    Route::post("/products", [ProductController::class, 'store']);
    Route::put("/products/{id}", [ProductController::class, 'update']);
    Route::delete("/products/{id}", [ProductController::class, 'destroy']);

    //Cart Routes
    Route::delete("/carts/{id}", [AdminCartController::class, "destroy"]);

    //category
    Route::post("/categories", [CategoryController::class, "store"]);
    Route::put("/categories/{id}", [CategoryController::class, "update"]);
    Route::delete("/categories/{id}", [CategoryController::class, "destroy"]);

    //profile
    Route::get("/profile", [UserController::class, "index"]);
    Route::put("/profile", [UserController::class, "update"]);


    //Users
    Route::get("/users", [UserController::class, "getAllUsers"]);
    Route::get("/users/{id}", [UserController::class, "show"]);
    Route::delete("/users/{id}", [UserController::class, "destroy"]);


    //Team Members
    Route::get("/team-members", [TeamMembersController::class, "index"]);
    Route::get("/team-members/{id}", [TeamMembersController::class, "show"]);
    Route::post("/team-members", [TeamMembersController::class, "store"]);
    Route::put("/team-members/{id}", [TeamMembersController::class, "update"]);
    Route::delete("/team-members/{id}", [TeamMembersController::class, "destroy"]);
});
