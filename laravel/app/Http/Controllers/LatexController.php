<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
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
        DB::table('tasks')->truncate();
        $parsedData = [];
        $latexFilesPath = public_path('/mathExamples/latex');
        $files = scandir($latexFilesPath);
        $setId = 0;
        foreach ($files as $file) {
            $setId++;
            if ($file !== '.' && $file !== '..') {
                $filePath = $latexFilesPath . '/' . $file;
                $latexContent = file_get_contents($filePath);

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

                        $task->save();
                    }

                } elseif (strpos($file, 'odozva') !== false) {

                }
            }
        }

        $json = json_encode($parsedData, JSON_UNESCAPED_UNICODE);
        return response($json)->header('Content-Type', 'application/json');
    }



    public function generateTasks()
    {

        $tasks = Task::inRandomOrder()->limit(5)->get();

        return response()->json($tasks);
    }
}
