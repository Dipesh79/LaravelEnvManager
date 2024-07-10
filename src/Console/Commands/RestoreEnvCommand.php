<?php

namespace Dipesh79\LaravelEnvManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * A console command to restore the .env file from a backup based on a given timestamp.
 *
 * This command facilitates the restoration of the Laravel application's .env file
 * from a previously created backup. The backup must have been created using the
 * env:backup command, which stores backups in a designated directory with a timestamp.
 * The user must provide the exact timestamp of the backup they wish to restore.
 */
class RestoreEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * This signature requires the user to provide a timestamp argument when calling
     * the command. The timestamp should match the one used in the filename of the
     * backup they wish to restore.
     *
     * @var string
     */
    protected $signature = 'env:restore {timestamp}';

    /**
     * The console command description.
     *
     * This description provides users with information about the command's functionality
     * when they list available commands via `php artisan`.
     *
     * @var string
     */
    protected $description = 'Restore the .env file from a backup';

    /**
     * Execute the console command.
     *
     * This method performs the restoration process. It constructs the path to the backup
     * file using the provided timestamp, checks if the file exists, and if so, copies it
     * to replace the current .env file. If the backup file does not exist, an error message
     * is displayed.
     */
    public function handle(): void
    {
        // Retrieve the timestamp argument provided by the user
        $timestamp = $this->argument('timestamp');
        // Construct the path to the backup file using the provided timestamp
        $backupPath = storage_path("app/env_backups/.env_{$timestamp}");

        // Check if the backup file exists
        if (File::exists($backupPath)) {
            // Copy the backup file to replace the current .env file
            File::copy($backupPath, base_path('.env'));
            // Inform the user that the backup has been successfully restored
            $this->info("Backup restored: .env_{$timestamp}");
        } else {
            // Display an error message if the backup file does not exist
            $this->error("Backup not found: .env_{$timestamp}");
        }
    }
}
