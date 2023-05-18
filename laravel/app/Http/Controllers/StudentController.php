<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        // Select rows from the "users" table where role is "student"
        $students = DB::table('users')->where('role', 'student')->get();

        // Return the students as JSON response
        return response()->json($students);
    }
}
