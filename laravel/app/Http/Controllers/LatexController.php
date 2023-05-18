<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                    $solutions = array_map(function ($solution) {
                        $solution = trim($solution);
                        $solution = preg_replace('/\s+/', ' ', $solution);
                    }, $solutions);

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


                    $pattern = '/\\\\begin\{task\}(.*?)\\\\begin\{equation\*\}/s';
                    preg_match_all($pattern, $latexContent, $matches);
                    $cleanedDescriptions = array_map(function ($match) {
                        return preg_replace('/\$.*?\$/s', '', $match);
                    }, $matches[1]);


                    $pattern = '/\\\\begin{equation\*}([\s\S]*?)\\\\end{equation\*}/';
                    preg_match_all($pattern, $latexContent, $matchesSolutions);
                    $solutions = $matchesSolutions[1];
                    $solutions = array_map(function ($solution) {
                        $solution = trim($solution);
                        $solution = preg_replace('/\s+/', ' ', $solution);
                        return $solution;
                    }, $solutions);


                    preg_match_all('/\\\\includegraphics\{(.*?)\}/', $latexContent, $matchesImages);
                    $imageFilenames = $matchesImages[1];

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
        $tasks = Task::inRandomOrder()->limit(5)->get();
        $user = Auth::user();

        foreach ($tasks as $task) {
            UserTask::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'state' => 'generated',
                'points' => 0,
                'solution' => null,
            ]);
        }

        return response()->json($tasks);
    }
}

