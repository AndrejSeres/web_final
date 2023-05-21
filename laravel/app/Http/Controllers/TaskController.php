<?php

namespace App\Http\Controllers;

use App\Models\UserTask;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {

        return view('student_tasks');
    }

    public function update(Request $request)
    {
        $setId = $request->input('setId');
        $points = $request->input('points');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $open = $request->input('open');

        $tasksCount = Task::where('setId', $setId)->count();
        $pointsPerTask = $tasksCount > 0 ? $points / $tasksCount : 0;

        $updateData = [
            'open' => $open,
            'points' => $pointsPerTask
        ];

        if ($dateFrom) {
            $updateData['date_from'] = $dateFrom;
        } else {
            $updateData['date_from'] = null;
        }

        if ($dateTo) {
            $updateData['date_to'] = $dateTo;
        } else {
            $updateData['date_to'] = null;
        }

        Task::where('setId', $setId)
            ->update($updateData);

        return redirect()->back()->with('success', 'Tasks updated successfully.');
    }

    public function compareSolution(Request $request)
    {
        $taskId = $request->input('taskId');
        $userSolution = str_replace('\frac', '\dfrac', $request->input('latexSolution'));


        $task = Task::find($taskId);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $savedSolution = $task->solution;
        $result = $userSolution == $savedSolution;

        return response()->json(['result' => $result, 'points' => $task->points]);
    }

    public function updateUserTask(Request $request)
    {
        $taskId = $request->input('taskId');

        $points = $request->input('points');

        $userTask = UserTask::where('user_id', auth()->user()->id)->where('task_id', $taskId)->first();


        if ($userTask) {
            $userTask->state = 'delivered';
            $userTask->solution = $request->input('userSolution');
            if ($request->input('result') === 'true') {
                $userTask->points = $points;
            } else {
                $userTask->points =  0;
            }




            $userTask->save();
        }

        return response()->json(['success' => true]);
    }

}
