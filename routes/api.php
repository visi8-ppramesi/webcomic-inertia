<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\PageController;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/checkuser', function(){
        return response()->json(auth()->user(), 200);
    })->name('api.user.check');

    Route::middleware('role:admin')->group(function(){

    });

    Route::middleware('role:user')->group(function(){

    });

    // Route::get('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/comic/favorite/{comicId}', [ComicController::class, 'toggleFavoriteComic'])->name('api.comic.favorite');
    Route::get('/comic/chapters/{comicId}', [ComicController::class, 'getComicChapters'])->name('api.comic.get.chapters');
    Route::get('/chapter/bookmark/{chapter}', [ChapterController::class, 'bookmarkChapter'])->name('api.chapter.bookmark');

    Route::get('/pages/{comicId}/{chapter}/', [PageController::class, 'getComicPages'])->name('api.pages.show');
    Route::get('/scene/{page}', [PageController::class, 'getPageScene'])->name('api.page.show.scene');

    Route::get('/comic/{comic}', [ComicController::class, 'show'])->name('api.comic.show');
    Route::patch('/comic/{comic}', [ComicController::class, 'update'])->name('api.comic.update');
    Route::delete('/comic/{comic}', [ComicController::class, 'destroy'])->name('api.comic.delete');
    Route::post('/comic', [ComicController::class, 'create'])->name('api.comic.create');

    Route::get('/page/{page}', [PageController::class, 'show'])->name('api.page.show');
    Route::patch('/page/{page}', [PageController::class, 'update'])->name('api.page.update');
    Route::delete('/page/{page}', [PageController::class, 'destroy'])->name('api.page.delete');
    Route::post('/page', [PageController::class, 'create'])->name('api.page.create');
    // Route::get('/pages/{comic}', [PageController::class, 'index'])->name('api.comic.show.pages');

    Route::patch('/author/{author}', [AuthorController::class, 'update'])->name('api.author.update');
    Route::delete('/author/{author}', [AuthorController::class, 'destroy'])->name('api.author.delete');

    Route::post('/author', [AuthorController::class, 'create'])->name('api.author.create');

    Route::get('/user/favorites', [ComicController::class, 'getFavorites'])->name('api.user.show.favorites');

    Route::get('/comic/bookmark/check/{comicId}', [ComicController::class, 'checkBookmarked'])->name('api.comic.check.bookmark');
    Route::get('/comic/purchased/check/{comicId}', [ComicController::class, 'checkPurchased'])->name('api.comic.check.purchased');
    Route::post('/comics/purchase', [ComicController::class, 'purchaseComics'])->name('api.comics.purchase');
    Route::post('/chapter/purchase', [ChapterController::class, 'purchaseChapter'])->name('api.chapter.purchase');
    Route::get('/previews/{comicId}', [ChapterController::class, 'index'])->name('api.comic.get.previews');
});

Route::middleware('role:visitor')->group(function(){

});

// Route::post('/register', [AuthController::class, 'register'])->name('api.register');
// Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::get('/comics', [ComicController::class, 'index'])->name('api.comics.list');

// Route::get('/pages', [PageController::class, 'index'])->name('api.');

Route::get('/author/{author}', [AuthorController::class, 'show'])->name('api.author.show');
Route::get('/authors', [AuthorController::class, 'index'])->name('api.authors.list');
Route::get('/chapter/checkar/{chapter}', [ChapterController::class, 'checkAr'])->name('api.chapter.check.ar');
Route::get('/comic/comments/{comic}', [ComicController::class, 'fetchComments'])->name('api.comic.fetch.comments');
Route::get('/chapter/comments/{chapter}', [ChapterController::class, 'fetchComments'])->name('api.chapter.fetch.comments');
