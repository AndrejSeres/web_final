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
// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('show.home');
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

//students info routes
Route::get('/show-students', [StudentController::class, 'index']);
Route::get('/student-detail/{studentId}', [StudentController::class, 'showStudentDetail']);
Route::put('/update-points/{userTaskId}/{taskId}', [StudentController::class, 'updatePoints']);

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('show.tasks');
Route::put('/tasks/update', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');

Route::get('/teacher', [App\Http\Controllers\TeacherController::class, 'index'])->name('show.admin');


Route::post('/compare-solution', [App\Http\Controllers\TaskController::class, 'compareSolution']);
Route::post('/update-user-task', [App\Http\Controllers\TaskController::class, 'updateUserTask']);


Route::post('/upload-file', [LatexController::class, 'uploadFile'])->name('upload.file');
