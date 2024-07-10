<?php

namespace Dipesh79\LaravelEnvManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * A console command to back up the current .env file.
 *
 * This command creates a backup of the Laravel application's current .env file
 * by copying it to a designated backup directory within the storage path. Each
 * backup file is timestamped to allow for easy identification and restoration.
 */
class BackupEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * This property defines how the command can be called from the CLI.
     * The command `php artisan env:backup` triggers this command.
     *
     * @var string
     */
    protected $signature = 'env:backup';

    /**
     * The console command description.
     *
     * This description will appear when running `php artisan list`, providing
     * users with information about what the command does.
     *
     * @var string
     */
    protected $description = 'Backup the .env file';

    /**
     * Execute the console command.
     *
     * This method performs the backup operation. It checks if the backup directory
     * exists, creates it if it doesn't, and then copies the current .env file to
     * this directory with a timestamp appended to the filename.
     */
    public function handle(): void
    {
        // Define the path where backups will be stored
        $backupPath = storage_path('app/env_backups');

        // Check if the backup directory exists, create it if not
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Generate a timestamp for the backup filename
        $timestamp = now()->format('Y-m-d_H-i-s');
        // Copy the current .env file to the backup directory with the timestamp
        File::copy(base_path('.env'), "{$backupPath}/.env_{$timestamp}");

        // Inform the user of the successful backup
        $this->info("Backup created: .env_{$timestamp}");
    }
}
