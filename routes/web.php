<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    // return view('welcome');
    return redirect('login');

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/****************************************************************************************
 *  Route web
 */

 /**
  * Users
  */
Route::post('user/update_profile_image', [ProfileController::class, 'update_profile_image'])->name('user.update_profile_image');

/**
 * Posts
 */
Route::get('post/{user_id}', [PostController::class, 'index'])->name('posts');
Route::post('post/', [PostController::class, 'store'])->name('post.store');
Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.delete');
Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
Route::patch('/post/{post}',[PostController::class, 'update'])->name('post.update');