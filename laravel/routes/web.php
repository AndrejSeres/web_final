<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('show.home');
Route::get('/welcome', [App\Http\Controllers\WelcomeController::class, 'index'])->name('show.welcome');

Route::get('/locale/{locale}', function ($locale) {
    \Session::put('locale', $locale);
    return redirect()->back();
});