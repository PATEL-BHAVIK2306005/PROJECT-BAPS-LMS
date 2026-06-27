<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\IpdcHackerrankProblem;
use App\Models\IpdcHackerrankSubmission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class IpdcHackerrankController extends Controller
{
    public static function seedDemoProblems()
    {
        if (IpdcHackerrankProblem::count() > 0) {
            return;
        }

        IpdcHackerrankProblem::create([
            'title' => 'Moral Backlog Evaluator',
            'description' => "Given a student's list of completed seva hours and academic backlogs, evaluate if they are eligible for the Gold Medal of Character. A student is eligible if they have completed at least 50 hours of selfless service (seva) and have exactly 0 backlogs.",
            'input_format' => "First line contains an integer S (seva hours).\nSecond line contains an integer B (active backlogs).",
            'constraints' => "0 <= S <= 1000\n0 <= B <= 15",
            'output_format' => "Print ELIGIBLE if the student meets the criteria, else print INELIGIBLE.",
            'sample_input' => "60\n0",
            'sample_output' => "ELIGIBLE",
            'difficulty' => 'Easy',
            'points' => 50,
            'test_cases' => [
                ['input' => "60\n0", 'output' => "ELIGIBLE"],
                ['input' => "45\n0", 'output' => "INELIGIBLE"],
                ['input' => "80\n2", 'output' => "INELIGIBLE"],
                ['input' => "50\n0", 'output' => "ELIGIBLE"],
                ['input' => "20\n5", 'output' => "INELIGIBLE"]
            ]
        ]);

        IpdcHackerrankProblem::create([
            'title' => 'Digital Diet Integrity Check',
            'description' => "An important aspect of IPDC is maintaining focus and a healthy digital diet. You are given a list of logs representing website URLs visited by a student during study hours and the time spent in minutes. If the total time spent on social media sites ('instagram', 'facebook', 'youtube') exceeds 60 minutes, the digital diet integrity check fails. Output the total entertainment time and the final check result.",
            'input_format' => "First line contains N (number of log entries).\nThe next N lines contain the site name and time spent in minutes separated by a space.",
            'constraints' => "1 <= N <= 100\n0 <= time <= 200",
            'output_format' => "First line: Total minutes spent on social media.\nSecond line: FAIL or PASS.",
            'sample_input' => "3\ninstagram 30\ngoogle 45\nyoutube 15",
            'sample_output' => "45\nPASS",
            'difficulty' => 'Medium',
            'points' => 100,
            'test_cases' => [
                ['input' => "3\ninstagram 30\ngoogle 45\nyoutube 15", 'output' => "45\nPASS"],
                ['input' => "2\nfacebook 40\nyoutube 30", 'output' => "70\nFAIL"],
                ['input' => "4\ninstagram 20\nfacebook 20\nyoutube 20\ngithub 120", 'output' => "60\nPASS"],
                ['input' => "1\ngoogle 90", 'output' => "0\nPASS"]
            ]
        ]);
    }

    public function createProblem()
    {
        self::seedDemoProblems();
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'hod', 'faculty'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        return view('admin.ipdc.create_problem');
    }

    public function storeProblem(Request $request)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'hod', 'faculty'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'points' => 'required|integer',
            'input_format' => 'nullable|string',
            'constraints' => 'nullable|string',
            'output_format' => 'nullable|string',
            'sample_input' => 'nullable|string',
            'sample_output' => 'nullable|string',
            'test_inputs' => 'required|array',
            'test_outputs' => 'required|array',
        ]);

        // Build test cases JSON
        $testCases = [];
        foreach ($request->test_inputs as $index => $input) {
            $testCases[] = [
                'input' => $input,
                'output' => $request->test_outputs[$index] ?? ''
            ];
        }

        IpdcHackerrankProblem::create([
            'title' => $request->title,
            'description' => $request->description,
            'input_format' => $request->input_format,
            'constraints' => $request->constraints,
            'output_format' => $request->output_format,
            'sample_input' => $request->sample_input,
            'sample_output' => $request->sample_output,
            'test_cases' => $testCases,
            'difficulty' => $request->difficulty,
            'points' => $request->points,
            'created_by' => session('staff_id')
        ]);

        return redirect('/admin/ipdc')->with('success', 'IPDC HackerRank Problem Statement successfully assigned.');
    }

    public function showProblem($id)
    {
        self::seedDemoProblems();
        $problem = IpdcHackerrankProblem::findOrFail($id);
        
        $userId = auth()->id() ?? session('demo_user_id') ?? 1;
        $submissions = IpdcHackerrankSubmission::where('user_id', $userId)
                                                ->where('problem_id', $id)
                                                ->latest()
                                                ->get();

        return view('student.ipdc.hackerrank_ide', compact('problem', 'submissions'));
    }

    public function runCode(Request $request, $id)
    {
        self::seedDemoProblems();
        $problem = IpdcHackerrankProblem::findOrFail($id);
        $code = $request->input('code');
        $language = strtolower($request->input('language', 'python'));
        $customInput = $request->input('custom_input', '');

        // Execute code against custom input
        $result = $this->executeCodeSandbox($code, $language, $customInput);

        return response()->json([
            'output' => $result['output'],
            'stderr' => $result['stderr'],
            'code' => $result['exit_code']
        ]);
    }

    public function submitCode(Request $request, $id)
    {
        $problem = IpdcHackerrankProblem::findOrFail($id);
        $code = $request->input('code');
        $language = strtolower($request->input('language', 'python'));
        $userId = auth()->id() ?? session('demo_user_id') ?? 1;

        $testCases = $problem->test_cases ?? [];
        if (empty($testCases)) {
            // Fallback default test case
            $testCases = [[
                'input' => $problem->sample_input ?? '',
                'output' => $problem->sample_output ?? ''
            ]];
        }

        $passedCount = 0;
        $totalCount = count($testCases);
        $failedCase = null;

        foreach ($testCases as $index => $tc) {
            $input = $tc['input'] ?? '';
            $expected = trim($tc['output'] ?? '');

            $result = $this->executeCodeSandbox($code, $language, $input);
            $actual = trim($result['output']);

            if ($result['exit_code'] !== 0) {
                $failedCase = [
                    'index' => $index + 1,
                    'error' => $result['stderr'] ?: 'Runtime execution error.'
                ];
                break;
            }

            // Normalise line endings for comparison
            $expectedNorm = str_replace("\r\n", "\n", $expected);
            $actualNorm = str_replace("\r\n", "\n", $actual);

            if ($expectedNorm === $actualNorm) {
                $passedCount++;
            } else {
                $failedCase = [
                    'index' => $index + 1,
                    'expected' => $expected,
                    'actual' => $actual,
                    'input' => $input
                ];
                break;
            }
        }

        $status = ($passedCount === $totalCount) ? 'Passed' : 'Failed';

        // Log submission in Database
        $submission = IpdcHackerrankSubmission::create([
            'user_id' => $userId,
            'problem_id' => $id,
            'code' => $code,
            'language' => $language,
            'status' => $status,
            'passed_test_cases' => $passedCount,
            'total_test_cases' => $totalCount
        ]);

        // Award XP to student if passed first time
        if ($status === 'Passed') {
            $priorPassed = IpdcHackerrankSubmission::where('user_id', $userId)
                                                    ->where('problem_id', $id)
                                                    ->where('status', 'Passed')
                                                    ->where('id', '!=', $submission->id)
                                                    ->exists();
            if (!$priorPassed) {
                $user = User::find($userId);
                if ($user) {
                    $user->xp = ($user->xp ?? 0) + $problem->points;
                    $user->level = floor($user->xp / 100) + 1;
                    $user->save();
                }
            }
        }

        return response()->json([
            'status' => $status,
            'passed_count' => $passedCount,
            'total_count' => $totalCount,
            'failed_case' => $failedCase,
            'points_awarded' => ($status === 'Passed' && !($priorPassed ?? false)) ? $problem->points : 0
        ]);
    }

    private function executeCodeSandbox($code, $language, $stdin = '')
    {
        $tempDir = storage_path('app/temp_code/' . Str::random(10));
        File::makeDirectory($tempDir, 0755, true);

        $output = '';
        $stderr = '';
        $exitCode = 0;

        try {
            if ($language === 'python' || $language === 'python3') {
                $filename = 'main.py';
                File::put($tempDir . '/' . $filename, $code);
                $command = "python " . escapeshellarg($tempDir . '/' . $filename);
            } elseif ($language === 'javascript' || $language === 'node') {
                $filename = 'main.js';
                File::put($tempDir . '/' . $filename, $code);
                $command = "node " . escapeshellarg($tempDir . '/' . $filename);
            } elseif ($language === 'php') {
                $filename = 'main.php';
                File::put($tempDir . '/' . $filename, $code);
                $command = "php " . escapeshellarg($tempDir . '/' . $filename);
            } elseif ($language === 'java') {
                $filename = 'Solution.java';
                File::put($tempDir . '/' . $filename, $code);
                $command = "java " . escapeshellarg($tempDir . '/' . $filename);
            } else {
                return ['output' => '', 'stderr' => 'Language not supported', 'exit_code' => 1];
            }

            // Write stdin to file
            $stdinFile = $tempDir . '/input.txt';
            if ($stdin !== '') {
                File::put($stdinFile, $stdin);
                $command .= ' < ' . escapeshellarg($stdinFile);
            }

            $descriptorSpec = [
                0 => ["pipe", "r"],  // stdin
                1 => ["pipe", "w"],  // stdout
                2 => ["pipe", "w"]   // stderr
            ];

            $process = proc_open($command, $descriptorSpec, $pipes, $tempDir);

            if (is_resource($process)) {
                $output = stream_get_contents($pipes[1]);
                $stderr = stream_get_contents($pipes[2]);

                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);

                $exitCode = proc_close($process);
            } else {
                $stderr = "Failed to spawn sandbox subprocess.";
                $exitCode = 1;
            }
        } catch (\Exception $e) {
            $stderr = $e->getMessage();
            $exitCode = 1;
        } finally {
            File::deleteDirectory($tempDir);
        }

        return [
            'output' => $output,
            'stderr' => $stderr,
            'exit_code' => $exitCode
        ];
    }
}
