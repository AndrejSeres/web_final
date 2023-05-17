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

       // $tasks;

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $latexFilesPath . '/' . $file;

                /* Parsing from LateX */
                $latexContent = file_get_contents($filePath);

                /*  Echo   */
                // var_dump($latexContent);

                /*  Parsing section name    */
                preg_match_all('/\\\\section\*?\{(.*?)\}/s', $latexContent, $matchesName);
                $sectionNames = $matchesName[1];

                /*  Parsing Formula     */
                preg_match_all('/\$(.*?)\$/s', $latexContent, $matchesFormula);
                $formulas = $matchesFormula[1];

                /*
                 *  Parsing task description, works only for first two files
                 */
                $pattern = '/begin\{task\}(.*?)\\\\includegraphics/s';
                preg_match_all($pattern, $latexContent, $matches);
                $pattern = '/\$.*?\$/s';
                $cleanedDescriptions = preg_replace($pattern, '', $matches[1]);


                /*  Parsing solution  */
                $pattern = '/\\\\begin{equation\*}([\s\S]*?)\\\\end{equation\*}/';
                preg_match_all($pattern, $latexContent, $matchesSolutions);
                $solutions = $matchesSolutions[1];

                // var_dump($solutions);


//                for ($i = 0; $i < count($sectionNames); $i++) {
//                    $task = [
//                        'name' => $sectionNames[$i],
//                    ];
//                    if (!empty($formulas[$i])) {
//                        $task['formula'] = $formulas[$i];
//                    }
//                    if (!empty($cleanedDescriptions[$i])) {
//                        $task['description'] = $cleanedDescriptions[$i];
//                    }
//                    if (!empty($solutions[$i])) {
//                        $task['solution'] = $solutions[$i];
//                    }
//                    $parsedData[] = $task;
//                }

                for ($i = 0; $i < count($sectionNames); $i++) {
                    $task = new Task([
                        'name' => $sectionNames[$i],
                        'formula' => $formulas[$i] ?? null,
                        'description' => $cleanedDescriptions[$i] ?? null,
                        'solution' => $solutions[$i] ?? null,
                    ]);

                    $task->save();
                }

            }
        }

        $json = json_encode($parsedData, JSON_UNESCAPED_UNICODE);
        return response($json)->header('Content-Type', 'application/json');
    }
}
