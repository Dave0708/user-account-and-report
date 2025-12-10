<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        try {
            // 1. Setup Paths
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "clinic_backup_{$timestamp}.sql";
            $tempPath = sys_get_temp_dir() . '\\' . $filename; 
            $mysqldumpPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

            // Verify mysqldump exists
            if (!file_exists($mysqldumpPath)) {
                return response()->json([
                    'error' => 'mysqldump not found at: ' . $mysqldumpPath,
                    'hint' => 'Please ensure XAMPP MySQL is installed correctly'
                ], 500);
            }

            // 2. Database Config - Get from .env file
            $dbHost = env('DB_HOST', 'localhost');
            $dbName = env('DB_DATABASE', 'clinic');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPassword = env('DB_PASSWORD', '');

            // 3. Build Command with proper escaping
            $command = "\"$mysqldumpPath\"";
            $command .= " --host=\"$dbHost\"";
            $command .= " --user=\"$dbUser\"";
            
            if (!empty($dbPassword)) {
                $command .= " --password=\"$dbPassword\"";
            }
            
            $command .= " --single-transaction";
            $command .= " --quick";
            $command .= " --lock-tables=false";
            $command .= " --result-file=\"$tempPath\"";
            $command .= " \"$dbName\"";
            $command .= " 2>&1";

            // 4. Execute backup
            $output = [];
            $exitCode = 0;
            exec($command, $output, $exitCode);

            // 5. Validate backup was created
            if ($exitCode !== 0) {
                return response()->json([
                    'status' => 'BACKUP FAILED',
                    'exitCode' => $exitCode,
                    'errors' => $output,
                    'command' => str_replace($dbPassword, '****', $command)
                ], 500);
            }

            if (!file_exists($tempPath) || filesize($tempPath) === 0) {
                return response()->json([
                    'error' => 'Backup file not created or is empty',
                    'path' => $tempPath
                ], 500);
            }

            // 6. Download and Delete
            return response()->download($tempPath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Backup Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}