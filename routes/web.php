<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ConnectsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimelineController;
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

// Route::get('/timeline', function () {
//     return view('timeline');
// })->middleware(['auth', 'verified'])->name('timeline');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/****************************************************************************************
 *  Route web
 */

 Route::middleware('auth')->group(function () {
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
    Route::get('/post/{post}/show', [PostController::class, 'show'])->name('post.show');

    /**
     * Connect
     */
    Route::get('connect', [ConnectsController::class, 'index'])->name('connect');
    Route::post('connect/search', [ConnectsController::class, 'search'])->name('search');
    Route::post('connect/follow', [ConnectsController::class, 'follow'])->name('follow');
    Route::post('connect/unfollow', [ConnectsController::class, 'unfollow'])->name('unfollow');
    Route::post('connect/search-more', [ConnectsController::class, 'searchMoreResults'])->name('search-more');

    /**
     * Likes
     */
    Route::get('post/{post_id}/toggle-like', [LikesController::class, 'store'])->name('post.like.store');
    Route::get('post/{post_id}/list-likes', [LikesController::class, 'list'])->name('post.like.list');

    /**
     * Comments
     */
    Route::post('post/comment', [CommentsController::class, 'store'])->name('post.comment.store');
    Route::post('post/comment/list', [CommentsController::class, 'list'])->name('post.comment.list');
    Route::post('post/comment/list-more', [CommentsController::class, 'listMore'])->name('post.comment.list-more');
    Route::post('post/comment/delete', [CommentsController::class, 'destroy'])->name('post.comment.delete');
    /**
     * Connections
     */
    Route::get('post/{user_id}/connections', [ConnectsController::class, 'connections'])->name('posts.connections');
    Route::get('post/{user_id}/connections/{page_number}', [ConnectsController::class, 'connectionsMoreResults'])->name('posts.connections.page');
    Route::get('post/{user_id}/followers', [ConnectsController::class, 'followers'])->name('posts.followers');
    Route::get('post/{user_id}/followers/{page_number}', [ConnectsController::class, 'followersMoreResults'])->name('posts.followers.page');
    Route::get('post/{user_id}/following', [ConnectsController::class, 'following'])->name('posts.following');
    Route::get('post/{user_id}/following/{page_number}', [ConnectsController::class, 'followingMoreResults'])->name('posts.following.page');
    /**
     * Timeline
     */
    Route::get('timeline/', [TimelineController::class, 'index'])->name('timeline');
    Route::get('timeline/{page_number}', [TimelineController::class, 'postsMoreResults'])->name('timeline.page');
    /**
     * Notifications
     */
    Route::get('/notifications/list-unread',  [NotificationController::class, 'listUnreadNotifications'])->name('notification.list.unread');
});




require __DIR__.'/auth.php';