<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to e health API',
    ]);
});

// public route for registering a new user
Route::post('/register', [AuthController::class, 'register']);

// public route for logging in a user
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    // protected route for getting the authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // protected route for logging out a user
    Route::post('/logout', [AuthController::class, 'logout']);

    // protected route for creating a new post
    Route::post('/posts', [PostController::class, 'store']);

    // protected route for getting all posts
    Route::get('/posts', [PostController::class, 'index']);

    // protected route for getting a single post
    Route::get('/posts/{id}', [PostController::class, 'show']);

    // protected route for updating a post
    Route::put('/posts/{id}', [PostController::class, 'update']);

    // protected route for deleting a post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
});
