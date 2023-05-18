<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LatexController;
use App\Http\Controllers\StudentController;
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



/*
*  Route for GET request controller
*/
// Route::get('/parsed-data', 'LatexController@getParsedData');
Route::get('/parsed-data', [LatexController::class, 'saveParsedData']);
Route::get('/generate-tasks', [LatexController::class, 'generateTasks']);
Route::get('/show-students', [StudentController::class, 'index']);



// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks');


