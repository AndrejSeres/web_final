<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LatexController extends Controller
{


        /*              EXAMPLE of data representation
         *   Object[0]
         *    [
         *       'name' -> 'B34A5A',
         *       'formula' -> 'F(s)=\\dfrac{Y(s)}{W(s)}',
         *       'description' -> 'Nájdite prenosovú funkciu  pre systém opísaný blokovou schémou:',
         *       'solution' -> '\dfrac{2s^2+13s+10}{s^3+7s^2+18s+15}'
         *    ]
         */

    public function saveParsedData()
    {
        $parsedData = [];
        $latexFilesPath = public_path('/mathExamples/latex');
        $files = scandir($latexFilesPath);

        $setId = 0;
        foreach ($files as $file) {

            if ($file !== '.' && $file !== '..') {
                $filePath = $latexFilesPath . '/' . $file;
                $latexContent = file_get_contents($filePath);
                $setId++;
                if (strpos($file, 'blokovka') !== false) {
                    preg_match_all('/\\\\section\*?\{(.*?)\}/s', $latexContent, $matchesName);
                    $sectionNames = $matchesName[1];

                    preg_match_all('/\$(.*?)\$/s', $latexContent, $matchesFormula);
                    $formulas = $matchesFormula[1];

                    $pattern = '/begin\{task\}(.*?)\\\\includegraphics/s';
                    preg_match_all($pattern, $latexContent, $matches);
                    $pattern = '/\$.*?\$/s';
                    $cleanedDescriptions = preg_replace($pattern, '', $matches[1]);

                    $pattern = '/\\\\begin{equation\*}([\s\S]*?)\\\\end{equation\*}/';
                    preg_match_all($pattern, $latexContent, $matchesSolutions);
                    $solutions = $matchesSolutions[1];
                    $solutions = array_map('trim', $solutions);

                    preg_match_all('/\\\\includegraphics\{(.*?)\}/', $latexContent, $matchesImages);
                    $imageFilenames = $matchesImages[1];
                    var_dump($imageFilenames);

                    for ($i = 0; $i < count($sectionNames); $i++) {
                        $description = isset($cleanedDescriptions[$i]) ? trim(str_replace('\\', '', $cleanedDescriptions[$i])) : null;
                        $task = new Task([
                            'name' => $sectionNames[$i],
                            'formula' => isset($formulas[$i]) ? '$$' . $formulas[$i] . '$$' : null,
                            'description' => $description ?? null,
                            'solution' => $solutions[$i] ?? null,
                            'points' => '5',
                            'setId' => $setId
                        ]);

                        if (isset($imageFilenames[$i])) {
                            $imagePath = $imageFilenames[$i];

                            $imageName = substr($imagePath, strrpos($imagePath, '/') + 1);

                            $task->image = '/mathExamples/images/' . $imageName;
                        }

                        $existingTask = Task::where('name', $task['name'])
                            ->where('setId', $task['setId'])
                            ->first();

                        if (!$existingTask) {
                            $task->save();
                        }

                    }

                } elseif (strpos($file, 'odozva') !== false) {
                    preg_match_all('/\\\\section\*?\{(.*?)\}/s', $latexContent, $matchesName);
                    $sectionNames = $matchesName[1];

                    $pattern = '/\\\\begin\{equation\*\}(.*?)\\\\end\{equation\*\}/s';
                    preg_match_all($pattern, $latexContent, $matches);
                    $formulas = $matches[1];
                    $formulas = array_map(function ($formula) {
                        $formula = trim($formula);
                        $formula = str_replace('\n', '', $formula);
                        $formula = preg_replace('/\s+/', ' ', $formula);
                        return $formula;
                    }, $formulas);


                    $pattern = '/\\\\begin\{task\}(.*?)\\\\end\{task\}/s';
                    preg_match_all($pattern, $latexContent, $matches);
                    $taskContents = $matches[1];

                    $cleanedDescriptions = array_map(function ($content) {
                        $content = preg_replace('/\\\\begin\{equation\*\}(.*?)\\\\end\{equation\*\}/s', '', $content);
                        $content = str_replace('\\', '', $content);
                        $content = preg_replace('/(\$)/', '$${1}', $content); // Add "$$" around "$" symbols
                        return trim($content);
                    }, $taskContents);

                    var_dump($cleanedDescriptions);


                    preg_match_all('/\\\\includegraphics\{(.*?)\}/', $latexContent, $matchesImages);
                    $imageFilenames = $matchesImages[1];

                    for ($i = 0; $i < count($sectionNames); $i++) {
                        $formulaIndex = $i * 2;
                        $solutionIndex = $i * 2 + 1;

                        $task = new Task([
                            'name' => $sectionNames[$i],
                            'formula' => isset($formulas[$formulaIndex]) ? '$$' . $formulas[$formulaIndex] . '$$' : null,
                            'description' => $cleanedDescriptions[$i] ?? null,
                            'solution' => $formulas[$solutionIndex] ?? null,
                            'points' => '5',
                            'setId' => $setId
                        ]);

                        if (isset($imageFilenames[$i])) {
                            $imagePath = $imageFilenames[$i];

                            // Extract the text after the last slash
                            $imageName = substr($imagePath, strrpos($imagePath, '/') + 1);

                            // Set the image path for the task
                            $task->image = '/mathExamples/images/' . $imageName;
                        }

                        $existingTask = Task::where('name', $task['name'])
                            ->where('setId', $task['setId'])
                            ->first();

                        if (!$existingTask) {
                            $task->save();
                        }

                    }

                }
            }
        }

        $json = json_encode($parsedData, JSON_UNESCAPED_UNICODE);
        return response($json)->header('Content-Type', 'application/json');
    }




    public function generateTasks()
    {
        $user = Auth::user();

        $generatedTasks = UserTask::where('user_id', $user->id)
            ->where('state', 'delivered')
            ->pluck('task_id')
            ->toArray();

        $tasks = Task::whereNotIn('id', $generatedTasks)
            ->where('open', 1)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('date_from')
                        ->orWhere('date_from', '<=', Carbon::today());
                })
                    ->where(function ($query) {
                        $query->whereNull('date_to')
                            ->orWhere('date_to', '>=', Carbon::today());
                    });
            })
            ->inRandomOrder()
            ->limit(1)
            ->get();

        foreach ($tasks as $task) {
            $existingUserTask = UserTask::where('user_id', $user->id)
                ->where('task_id', $task->id)
                ->first();

            if (!$existingUserTask) {
                UserTask::create([
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'state' => 'generated',
                    'points' => 0,
                    'solution' => null,
                ]);
            }
        }

        return response()->json($tasks);
    }

}

