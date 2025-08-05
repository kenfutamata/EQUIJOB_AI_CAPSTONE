<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Process\Exceptions\ProcessFailedException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

use function Illuminate\Log\log;

class JobProviderGenerateReports extends Controller
{

    public function index()
    {
        return view('users.job-provider.job_provider_generate_report');
    }
    public function generateReport(Request $request)
    {
        $user = Auth::guard('job_provider')->user();

        // These paths must be correct.
        $pythonExecutable = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('scripts/job-provider-report-generation.py');

        if (!file_exists($pythonExecutable) || !file_exists($scriptPath)) {
            Log::error("Python executable or script not found. Check paths.");
            return redirect()->back()->with('error', 'Report generation is not configured correctly.');
        }

        $db_config = [
            'host'     => env('DB_HOST'),
            'port'     => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ];

        // --- THIS IS THE FINAL FIX ---
        // Revert to using the array-based Process constructor.
        // This is often more reliable when executed by a web server process.
        $process = new Process([
            $pythonExecutable,
            $scriptPath,
            json_encode($user->id), // Argument 1: User ID as a JSON string
            json_encode($db_config), // Argument 2: DB config as a JSON string
        ]);
        // --- END OF FIX ---

        $process->setWorkingDirectory(base_path());
        $process->setTimeout(120);

        try {
            $process->mustRun();
            $output = $process->getOutput();
            $result = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON from Python. Raw Output: ' . $output);
            }

            if (isset($result['status']) && $result['status'] === 'success') {
                $filepath = $result['filepath'];
                if ($filepath === null) {
                    return redirect()->back()->with('info', $result['message']);
                }
                $fullpath = Storage::disk('public')->path($filepath);
                if (!file_exists($fullpath)) {
                    throw new \Exception('Python script reported success, but the output file is missing.');
                }
                return response()->download($fullpath)->deleteFileAfterSend(true);
            } else {
                throw new \Exception($result['message'] ?? 'Unknown Python error');
            }
        } catch (ProcessFailedException | \Exception $e) {
            Log::error('Report Generation Failed: ' . $e->getMessage());
            if (isset($process) && $process->isStarted()) {
                Log::error('Python Stderr: ' . $process->getErrorOutput());
            }
            return redirect()->back()->with('error', 'The report could not be generated due to a server error.');
        }
    }
}
