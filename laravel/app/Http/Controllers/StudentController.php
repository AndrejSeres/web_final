<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserTask;
use App\Models\User;

class StudentController extends Controller
{
public function index()
{
    // Select rows from the "users" table where role is "student"
    $students = User::where('role', 'student')->get();

    // Iterate over each student and fetch the generated and delivered task counts and total points
    foreach ($students as $student) {
        $generatedTasks = UserTask::where('user_id', $student->id)->count();
        $deliveredTasks = UserTask::where('user_id', $student->id)->where('state', 'delivered')->count();
        $points = UserTask::where('user_id', $student->id)->sum('points');

        $student->generatedTasks = $generatedTasks;
        $student->deliveredTasks = $deliveredTasks;
        $student->points = $points;
    }

    // Return the students as JSON response
    return response()->json($students);
}

    public function showStudentDetail($studentId)
    {
        // Retrieve the student details based on the studentId and pass it to the student_detail blade
        $student = \App\Models\User::findOrFail($studentId);

        return view('student_detail', compact('student'));
    }

    /**
     * Update the points for a user task.
     *
     * @param Request $request
     * @param UserTask $userTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePoints(Request $request, $userTaskId, $taskId)
    {
        $validatedData = $request->validate([
            'points' => 'required|integer|min:0',
        ]);

        $userTask = UserTask::where('user_id', $userTaskId)
                            ->where('task_id', $taskId)
                            ->firstOrFail();

        $userTask->points = $validatedData['points'];
        $userTask->save();

        return response()->json(['points' => $userTask->points]);
    }
}
