<?php

use App\Http\Controllers\ViewController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->name('dashboard');

Route::get('/', [ViewController::class, 'viewDashboard'])->name('web.dashboard');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('web.aboutus');

Route::get('/scene/{page}', function () {
    return Inertia::render('SceneShow');
})->name('web.sceneshow');

Route::get('/search', function () {
    return Inertia::render('Search');
})->name('web.search');

Route::get('/comic/{comic}', function () {
    return Inertia::render('ComicShow');
})->name('web.comicshow');

Route::get('/page/{comic}/{chapter}', function () {
    return Inertia::render('PageShow');
})->name('web.pageshow');

Route::get('/author/{author}', function () {
    return Inertia::render('AuthorShow');
})->name('web.authorshow');

Route::get('/payment', function () {
    return Inertia::render('Payment');
})->name('web.payment');

Route::get('/mycomic', function () {
    return Inertia::render('MyComic');
})->name('web.mycomic');

Route::get('/account', function () {
    return Inertia::render('Account');
})->name('web.account');

Route::get('/privacy', function () {
    return Inertia::render('Privacy');
})->name('web.privacypolicy');

Route::get('/404', function () {
    return Inertia::render('NotFound');
})->name('web.notfound');

// Route::get('/register', function () {
//     return Inertia::render('Auth/Register');
// })->name('web.register');

// Route::get('/login', function () {
//     return Inertia::render('Auth/Login');
// })->name('web.login');

// Route::get('/logout', function () {
//     return Inertia::render('Logout');
// })->name('web.logout');
