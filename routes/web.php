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
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/comic/{comic}', [ViewController::class, 'viewComicShow'])->name('web.comic');
    Route::get('/page/{comic}/{chapter}', [ViewController::class, 'viewPageShow'])->name('web.page');
});

Route::get('/about', [ViewController::class, 'viewAboutShow'])->name('web.aboutus');
Route::get('/scene/{page}', [ViewController::class, 'viewSceneShow'])->name('web.scene');
Route::get('/search', [ViewController::class, 'viewSearchShow'])->name('web.search');
Route::get('/author/{author}', [ViewController::class, 'viewAuthorShow'])->name('web.author');
Route::get('/payment', [ViewController::class, 'viewPaymentShow'])->name('web.payment');
Route::get('/mycomic', [ViewController::class, 'viewMyComicShow'])->name('web.mycomic');
Route::get('/account', [ViewController::class, 'viewAccountShow'])->name('web.account');
Route::get('/privacy', [ViewController::class, 'viewPrivacyShow'])->name('web.privacypolicy');

// Route::get('/404', [ViewController::class, 'view404Show'])->name('web.notfound');

// Route::get('/register', function () {
//   return Inertia::render('Auth/Register');
// })->name('web.register');

// Route::get('/login', function () {
//     return Inertia::render('Auth/Login');
// })->name('web.login');

// Route::get('/logout', function () {
//     return Inertia::render('Logout');
// })->name('web.logout');
