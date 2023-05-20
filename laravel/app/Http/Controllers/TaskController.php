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
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $open = $request->input('open');

        Task::where('setId', $setId)
            ->update([
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'open' => $open
            ]);

        return redirect()->back()->with('success', 'Tasks updated successfully.');
    }

}
