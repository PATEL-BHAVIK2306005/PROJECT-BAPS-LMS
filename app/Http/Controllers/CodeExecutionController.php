<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\CodeExecution;

class CodeExecutionController extends Controller
{
    public function execute(Request $request)
    {
        // 1. API Key Validation
        $providedKey = $request->header('X-API-Key') ?? $request->input('api_key');
        $validKey = env('VITE_WEBCONTAINER_API_KEY', 'wc_api_www.bhavikpatel180_2ef44332bd5b54bc0e0ee86dd27ddf79');
        
        if ($providedKey !== $validKey) {
            return response()->json(['message' => 'Unauthorized. Invalid API Key.'], 401);
        }

        $language = strtolower($request->input('language', 'python'));
        $files = $request->input('files', []);
        $stdin = $request->input('stdin', '');

        if (empty($files) || !isset($files[0]['content'])) {
            return response()->json(['message' => 'No code provided.'], 400);
        }

        $code = $files[0]['content'];
        $tempDir = storage_path('app/temp_code/' . Str::random(10));
        File::makeDirectory($tempDir, 0755, true);

        $output = '';
        $stderr = '';
        $codeStatus = 0;

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
                // Java Execution Backend
                $filename = 'Solution.java'; // Many online judges use Solution.java
                File::put($tempDir . '/' . $filename, $code);
                // In Java 11+, you can compile and run in a single command
                $command = "java " . escapeshellarg($tempDir . '/' . $filename);
            } else {
                return response()->json(['message' => 'Language not supported locally.'], 400);
            }

            // Write stdin to a file if provided
            $stdinFile = $tempDir . '/input.txt';
            if (!empty($stdin)) {
                File::put($stdinFile, $stdin);
                $command .= ' < ' . escapeshellarg($stdinFile);
            }

            // Execute command
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

                $codeStatus = proc_close($process);
            } else {
                $stderr = "Failed to start execution process.";
                $codeStatus = 1;
            }
        } catch (\Exception $e) {
            $stderr = $e->getMessage();
            $codeStatus = 1;
        } finally {
            // Cleanup
            File::deleteDirectory($tempDir);
        }

        // 2. Link to Database (Save Execution Log)
        CodeExecution::create([
            'language' => $language,
            'code' => $code,
            'output' => $output,
            'stderr' => $stderr,
            'status_code' => $codeStatus,
            'api_key_used' => $providedKey,
        ]);

        return response()->json([
            'run' => [
                'output' => $output,
                'stderr' => $stderr,
                'code' => $codeStatus
            ]
        ]);
    }
}
