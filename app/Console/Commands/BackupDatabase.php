<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup MySQL database';

    public function handle_old()
    {
        $filename = 'backup_' . date('Y_m_d_His') . '.sql';
        $path = storage_path('app/backups');

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $mysqldump = '"C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe"';

        $command = sprintf(
            '%s -u%s -p%s %s > "%s\\%s"',
            $mysqldump,
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $path,
            $filename
        );

        exec($command . ' 2>&1', $output, $result);

        if ($result !== 0) {
            \Log::error('DB Backup failed', $output);
            $this->error('Backup failed');
        } else {
            $this->info('Database backup created successfully');
        }
    }
    public function handle()
{
    $backupPath = storage_path('app/backups');

    if (!file_exists($backupPath)) {
        mkdir($backupPath, 0755, true);
    }

    $timestamp = date('Y_m_d_His');
    $sqlFile = "$backupPath/backup_$timestamp.sql";
    $zipFile = "$backupPath/backup_$timestamp.zip";

    $mysqldump = '"C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe"';

    $command = sprintf(
        '%s -u%s -p%s %s > "%s"',
        $mysqldump,
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
        $sqlFile
    );

    exec($command . ' 2>&1', $output, $result);

    if ($result !== 0 || !file_exists($sqlFile)) {
        \Log::error('DB backup failed', $output);
        $this->error('Database backup failed');
        return;
    }

    // ZIP compression
    $zip = new \ZipArchive();
    if ($zip->open($zipFile, \ZipArchive::CREATE) === true) {
        $zip->addFile($sqlFile, basename($sqlFile));
        $zip->close();
        unlink($sqlFile); // delete .sql after zip
    } else {
        $this->error('ZIP creation failed');
        return;
    }

    // Delete backups older than 7 days
    $files = glob($backupPath . '/*.zip');
    $now = time();

    foreach ($files as $file) {
        if ($now - filemtime($file) > (7 * 24 * 60 * 60)) {
            unlink($file);
        }
    }

    \Log::info('Database backup completed successfully');
    $this->info('Database backup completed successfully');
}
}
