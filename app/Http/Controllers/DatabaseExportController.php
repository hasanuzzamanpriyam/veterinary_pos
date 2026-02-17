<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\DbDumper\Databases\MySql;

class DatabaseExportController extends Controller
{
    public function index()
    {
        return view('admin.export', get_defined_vars());
    }

    /**
     * Exports the database into a SQL file.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        try {
            $filePath = storage_path('app/backups');
            $fileName = 'database_backup_' . now()->format('Y_m_d_H_i_s') . '.sql';
            $fullPath = $filePath . DIRECTORY_SEPARATOR . $fileName;

            if (!File::exists($filePath)) {
                File::makeDirectory($filePath, 0777, true);
            }

            // Try Spatie dumper first
            if ($this->exportWithSpatie($fullPath)) {
                return response()->download($fullPath)->deleteFileAfterSend(true);
            }

            // Fallback to manual export
            Log::warning('Spatie export failed, using manual fallback');
            if ($this->exportManually($fullPath)) {
                return response()->download($fullPath)->deleteFileAfterSend(true);
            }

            throw new \Exception('All export methods failed.');

        } catch (\Exception $e) {
            Log::error('Database export failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'os' => PHP_OS,
                'php_version' => PHP_VERSION
            ]);

            return response()->json([
                'error' => 'Database export failed: ' . $e->getMessage(),
                'php_version' => PHP_VERSION,
                'os' => PHP_OS,
                'suggestion' => $this->getErrorSuggestion($e->getMessage()),
                'alternative' => 'Try manual export at: ' . url('/database/export/manual')
            ], 500);
        }
    }

    /**
     * Try export using Spatie DbDumper
     */
    private function exportWithSpatie(string $fullPath): bool
    {
        try {
            $dbConfig = config('database.connections.mysql');
            $host = $dbConfig['host'] ?? '127.0.0.1';
            $database = $dbConfig['database'];
            $username = $dbConfig['username'] ?? 'root';
            $password = $dbConfig['password'] ?? '';

            $dumper = $this->createCrossPlatformDumper($host, $database, $username, $password);
            
            // Set mysqldump binary path if found
            $mysqldumpPath = $this->findMysqldumpBinary();
            if ($mysqldumpPath) {
                $dumper->setDumpBinaryPath(dirname($mysqldumpPath));
            }

            // Add compatibility options
            $dumper->addExtraOption('--no-tablespaces')
                   ->addExtraOption('--column-statistics=0')
                   ->addExtraOption('--skip-comments')
                   ->addExtraOption('--default-character-set=utf8mb4')
                   ->addExtraOption('--skip-lock-tables')
                   ->addExtraOption('--single-transaction');

            // Execute dump
            $dumper->dumpToFile($fullPath);

            // Verify the file was created
            if (File::exists($fullPath) && File::size($fullPath) > 0) {
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::warning('Spatie export failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create cross-platform MySQL dumper
     */
    private function createCrossPlatformDumper(string $host, string $database, string $username, string $password): MySql
    {
        $dbConfig = config('database.connections.mysql');
        $port = $dbConfig['port'] ?? '3306';
        $socket = $dbConfig['unix_socket'] ?? null;

        $dumper = MySql::create()
            ->setDbName($database)
            ->setUserName($username)
            ->setPassword($password);

        // Platform-specific host handling
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows always uses TCP/IP, no socket support
            $dumper->setHost($host)->setPort($port);
        } else {
            // Linux/Mac handling
            if ($socket && File::exists($socket)) {
                // Use socket if available
                $dumper->setSocket($socket);
            } else {
                // Use TCP
                $dumper->setHost($host)->setPort($port);
            }
        }

        return $dumper;
    }

    /**
     * Find mysqldump binary path (cross-platform)
     */
    private function findMysqldumpBinary(): ?string
    {
        // Common paths for different platforms and installations
        $possiblePaths = [];

        if (PHP_OS_FAMILY === 'Windows') {
            // Windows paths
            $possiblePaths = array_merge($possiblePaths, [
                // Laragon paths (adjust based on your installation)
                'D:/laragon/bin/mysql/mysql-8.4.3/bin/mysqldump.exe',
                'D:/laragon/bin/mysql/mysql-8.4.0/bin/mysqldump.exe',
                'D:/laragon/bin/mysql/mysql-8.0/bin/mysqldump.exe',
                'D:/laragon/bin/mysql/mysql-5.7/bin/mysqldump.exe',
                'C:/laragon/bin/mysql/mysql-8.4.3/bin/mysqldump.exe',
                'C:/laragon/bin/mysql/mysql-8.4.0/bin/mysqldump.exe',
                'C:/laragon/bin/mysql/mysql-8.0/bin/mysqldump.exe',
                'C:/laragon/bin/mysql/mysql-5.7/bin/mysqldump.exe',
                
                // XAMPP
                'C:/xampp/mysql/bin/mysqldump.exe',
                
                // WAMP
                'C:/wamp64/bin/mysql/mysql8.4.0/bin/mysqldump.exe',
                'C:/wamp/bin/mysql/mysql8.4.0/bin/mysqldump.exe',
                
                // MySQL Installer
                'C:/Program Files/MySQL/MySQL Server 8.4/bin/mysqldump.exe',
                'C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.exe',
                'C:/Program Files/MySQL/MySQL Server 5.7/bin/mysqldump.exe',
                
                // Common program files locations
                'C:/ProgramData/MySQL/MySQL Server 8.4/bin/mysqldump.exe',
                'C:/ProgramData/MySQL/MySQL Server 8.0/bin/mysqldump.exe',
            ]);
            
            // Check system PATH
            $pathCheck = shell_exec('where mysqldump.exe 2>nul');
            if (!empty($pathCheck)) {
                $paths = explode("\n", trim($pathCheck));
                foreach ($paths as $path) {
                    if (file_exists(trim($path))) {
                        return trim($path);
                    }
                }
            }
        } else {
            // Linux/Mac paths
            $possiblePaths = array_merge($possiblePaths, [
                '/usr/bin/mysqldump',
                '/usr/local/bin/mysqldump',
                '/usr/local/mysql/bin/mysqldump',
                '/opt/homebrew/bin/mysqldump',
                '/opt/lampp/bin/mysqldump',
                '/Applications/XAMPP/bin/mysqldump',
                '/Applications/MAMP/Library/bin/mysqldump',
                '/usr/bin/mysqldump-8.4',
                '/usr/bin/mysqldump-8.0',
                '/usr/bin/mysqldump-5.7',
            ]);
            
            // Check system PATH using which
            $pathCheck = shell_exec('which mysqldump 2>/dev/null');
            if (!empty($pathCheck)) {
                $paths = explode("\n", trim($pathCheck));
                foreach ($paths as $path) {
                    if (file_exists(trim($path))) {
                        return trim($path);
                    }
                }
            }
        }

        // Check hardcoded paths
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Try to find in common directories using glob
        $globPatterns = [];
        if (PHP_OS_FAMILY === 'Windows') {
            $globPatterns = [
                'D:/laragon/bin/mysql/*/bin/mysqldump.exe',
                'C:/laragon/bin/mysql/*/bin/mysqldump.exe',
                'C:/Program Files/MySQL/MySQL Server */bin/mysqldump.exe',
                'C:/Program Files (x86)/MySQL/MySQL Server */bin/mysqldump.exe',
            ];
        } else {
            $globPatterns = [
                '/usr/local/mysql*/bin/mysqldump',
                '/opt/mysql*/bin/mysqldump',
                '/usr/bin/mysqldump*',
            ];
        }

        foreach ($globPatterns as $pattern) {
            $found = glob($pattern);
            if (!empty($found) && file_exists($found[0])) {
                return $found[0];
            }
        }

        return null;
    }

    /**
     * Manual export using pure PHP/Laravel (fallback method)
     */
    private function exportManually(string $fullPath): bool
    {
        try {
            $output = $this->generateSqlBackup();
            
            if (empty($output)) {
                throw new \Exception('Generated SQL is empty');
            }
            
            $bytesWritten = File::put($fullPath, $output);
            
            return $bytesWritten > 0 && File::exists($fullPath);

        } catch (\Exception $e) {
            Log::error('Manual export failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate SQL backup using Laravel's database connection
     */
    private function generateSqlBackup(): string
    {
        $output = "-- MySQL dump generated by Laravel Backup System\n";
        $output .= "-- Generated: " . now()->toDateTimeString() . "\n";
        $output .= "-- Host: " . config('database.connections.mysql.host') . "\n";
        $output .= "-- Database: " . config('database.connections.mysql.database') . "\n";
        $output .= "-- PHP Version: " . PHP_VERSION . "\n";
        $output .= "-- OS: " . PHP_OS . "\n\n";
        
        $output .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
        $output .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
        $output .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
        $output .= "/*!40101 SET NAMES utf8mb4 */;\n";
        $output .= "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n";
        $output .= "/*!40103 SET TIME_ZONE='+00:00' */;\n";
        $output .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n";
        $output .= "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n";
        $output .= "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n";
        $output .= "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n\n";
        
        $databaseName = config('database.connections.mysql.database');
        
        // Get all tables
        try {
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $databaseName;
            
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                $output .= $this->exportTableStructure($tableName);
                $output .= $this->exportTableData($tableName);
            }
            
            $output .= "\n/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n";
            $output .= "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n";
            $output .= "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n";
            $output .= "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n";
            $output .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
            $output .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
            $output .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";
            $output .= "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n";
            
            return $output;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate SQL backup: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export table structure
     */
    private function exportTableStructure(string $tableName): string
    {
        $output = "\n--\n-- Table structure for table `{$tableName}`\n--\n\n";
        $output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
        
        try {
            $createResult = DB::selectOne("SHOW CREATE TABLE `{$tableName}`");
            if ($createResult && isset($createResult->{'Create Table'})) {
                $output .= $createResult->{'Create Table'} . ";\n";
            } else {
                $output .= "-- Could not retrieve table structure for `{$tableName}`\n";
            }
        } catch (\Exception $e) {
            $output .= "-- Error retrieving structure for `{$tableName}`: " . $e->getMessage() . "\n";
        }
        
        return $output;
    }

    /**
     * Export table data
     */
    private function exportTableData(string $tableName): string
    {
        $output = "\n--\n-- Dumping data for table `{$tableName}`\n--\n\n";
        
        try {
            // Lock table for read (prevents data changes during dump)
            DB::statement("LOCK TABLES `{$tableName}` READ");
            
            $rows = DB::table($tableName)->get();
            
            if ($rows->count() === 0) {
                $output .= "-- Table `{$tableName}` is empty\n\n";
                DB::statement("UNLOCK TABLES");
                return $output;
            }
            
            // Get column names
            $columns = array_keys((array)$rows->first());
            $columnList = '`' . implode('`, `', $columns) . '`';
            
            $insertStatements = [];
            foreach ($rows as $row) {
                $values = [];
                foreach ($columns as $column) {
                    $value = $row->$column;
                    if (is_null($value)) {
                        $values[] = 'NULL';
                    } elseif (is_numeric($value)) {
                        $values[] = $value;
                    } else {
                        // Escape single quotes and backslashes
                        $escaped = str_replace(['\\', "'"], ['\\\\', "''"], $value);
                        $values[] = "'" . $escaped . "'";
                    }
                }
                $insertStatements[] = "INSERT INTO `{$tableName}` ({$columnList}) VALUES (" . implode(', ', $values) . ");";
            }
            
            DB::statement("UNLOCK TABLES");
            
            // Group inserts for better performance
            $output .= implode("\n", $insertStatements) . "\n\n";
            
        } catch (\Exception $e) {
            $output .= "-- Error dumping data for `{$tableName}`: " . $e->getMessage() . "\n\n";
        }
        
        return $output;
    }

    /**
     * Manual export endpoint
     */
    public function manualExport()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1024M');
        
        try {
            $filePath = storage_path('app/backups');
            $fileName = 'manual_backup_' . now()->format('Y_m_d_H_i_s') . '.sql';
            $fullPath = $filePath . DIRECTORY_SEPARATOR . $fileName;

            if (!File::exists($filePath)) {
                File::makeDirectory($filePath, 0777, true);
            }

            if ($this->exportManually($fullPath)) {
                return response()->download($fullPath)->deleteFileAfterSend(true);
            }

            throw new \Exception("Manual export failed.");
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Manual export failed: ' . $e->getMessage(),
                'solution' => 'Please check database connection and permissions.'
            ], 500);
        }
    }

    /**
     * Get error suggestions
     */
    private function getErrorSuggestion(string $error): string
    {
        $errorLower = strtolower($error);
        
        if (strpos($errorLower, 'socket') !== false || strpos($errorLower, '10106') !== false) {
            $suggestions = [
                'Windows: Run "netsh winsock reset" as Administrator and restart',
                'Check if MySQL service is running',
                'For Laragon: Make sure Laragon is running and MySQL is started',
                'Try the manual export option'
            ];
            return implode('. ', $suggestions);
        }
        
        if (strpos($errorLower, 'access denied') !== false) {
            return 'Check MySQL credentials in .env file. Verify username and password.';
        }
        
        if (strpos($errorLower, 'cannot connect') !== false) {
            return 'Check if MySQL server is running. For Laragon, start MySQL from the tray icon.';
        }
        
        if (strpos($errorLower, 'mysqldump') !== false) {
            return 'mysqldump not found. Install MySQL client tools or use manual export.';
        }
        
        return 'Try the manual export option or check server error logs.';
    }

    /**
     * Get backup files list (optional helper)
     */
    public function listBackups()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            return response()->json(['backups' => []]);
        }
        
        $files = File::files($backupPath);
        $backups = [];
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    'path' => $file->getPathname()
                ];
            }
        }
        
        // Sort by modification time (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });
        
        return response()->json(['backups' => $backups]);
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}