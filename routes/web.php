<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Route - Home
Route::get('/', function () {
    return view('welcome');
});

// Admin Dashboard (protected by 'auth' and 'verified' middleware)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes for users
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('books', BookController::class);
    Route::get('books/{book}/comments', [CommentController::class, 'index'])->name('comments.index');  // Show comments for a book
    Route::post('books/{book}/comments', [CommentController::class, 'store'])->name('comments.store');  // Store a comment for a book
    Route::post('books/{book}/rate', [BookController::class, 'rate'])->name('books.rate');  // Rate a book (1 to 5 stars)
});

require __DIR__ . '/auth.php';
