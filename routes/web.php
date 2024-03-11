<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopHeadlineController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserFavouriteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome',);
});

//Auth middleware
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //topHeadlines
    Route::get('/topHeadlines', [TopHeadlineController::class, 'index'])->name('topHeadlines.index');
    //News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    //Favourites
    Route::get('/favourites', [UserFavouriteController::class, 'index'])->name('favourites.index');
    Route::post('/favourites', [UserFavouriteController::class, 'store'])->name('favourites.store');
    Route::delete('/favourites/{id}', [UserFavouriteController::class, 'destroy'])->name('favourites.destroy');
    //Comments
    Route::post('/comments', [UserCommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{id}', [UserCommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [UserCommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/comments/{id}/edit', [UserCommentController::class, 'edit'])->name('comments.edit');
});

//Admin and Auth middleware
Route::group(['middleware' => ['auth', 'admin']], function () {
    //Admin Backoffice
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/users/{id}', [AdminController::class, 'show'])->name('admin.show');
    Route::get('/admin/users/{id}/favourites', [AdminController::class, 'getFavourites'])->name('admin.users.favourites');
    Route::get('/admin/users/{id}/comments', [AdminController::class, 'getComments'])->name('admin.users.comments');
    Route::get('/admin/users/{id}/logs', [AdminController::class, 'getLogs'])->name('admin.users.logs');
});


require __DIR__ . '/auth.php';
