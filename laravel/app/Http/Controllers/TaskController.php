<?php

namespace App\Http\Controllers;

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



}
