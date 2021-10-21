<?php

use App\Helpers\Helpers;
use App\Helpers\Uploader;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TokenTransactionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VoteController;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

    Route::get('/comic/subscribe/{comic}', [ComicController::class, 'toggleSubscribeComic'])->name('api.comic.subscribe');
    Route::get('/comic/favorite/{comic}', [ComicController::class, 'toggleFavoriteComic'])->name('api.comic.favorite');
    Route::get('/chapter/favorite/{chapter}', [ChapterController::class, 'toggleFavoriteChapter'])->name('api.chapter.favorite');
    Route::get('/comic/chapters/{comic}', [ComicController::class, 'getComicChapters'])->name('api.comic.get.chapters');
    Route::get('/chapter/bookmark/{chapter}', [ChapterController::class, 'bookmarkChapter'])->name('api.chapter.bookmark');

    Route::get('/pages/{comic}/{chapter}/', [PageController::class, 'getComicPages'])->name('api.pages.show');
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

    Route::get('/comic/bookmark/check/{comic}', [ComicController::class, 'checkBookmarked'])->name('api.comic.check.bookmark');
    Route::get('/comic/purchased/check/{comic}', [ComicController::class, 'checkPurchased'])->name('api.comic.check.purchased');
    Route::post('/comics/purchase', [ComicController::class, 'purchaseComics'])->name('api.comics.purchase');
    Route::post('/chapter/purchase', [ChapterController::class, 'purchaseChapter'])->name('api.chapter.purchase');
    Route::get('/previews/{comic}', [ChapterController::class, 'index'])->name('api.comic.get.previews');
    Route::post('/tokens/purchase', [TokenTransactionController::class, 'purchaseTokens'])->name('api.tokens.purchase');

    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('api.comment.delete');
    Route::post('/comment/{comment}/vote', [VoteController::class, 'vote'])->name('api.comment.vote');
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
Route::get('/search', [SearchController::class, 'fetchSearch'])->name('api.search');
Route::get('/comic/image/{filename}', [FileController::class, 'fetchPageImage'])->name('api.image.fetch');
Route::get('/settings/{name}', [SettingController::class, 'getSetting'])->name('api.settings');
Route::get('/genres', function(){
    return response()->json(Genre::all());
})->name('api.genres');
Route::get('/tags', function(){
    return response()->json(Tag::all());
})->name('api.tags');

Route::post('/testing', function(Request $request){
    $input = $request->input();
    $retval = [];
    foreach($input['files'] as $idx => $file){
        $retval[] = Uploader::saveBase64File($file, 'storage/media/');
    }
    return response()->json($retval, 200);
})->name('api.testing');
