<?php

namespace Dipesh79\LaravelEnvManager\Console\Commands;

use Illuminate\Console\Command;

/**
 * A console command to remove a specified environment variable from the .env file.
 *
 * This command allows for the removal of an environment variable by key. It is
 * useful for dynamically managing application settings without directly editing
 * the .env file. If the specified key does not exist, no changes are made.
 */
class RemoveEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * This signature specifies that the command will be called as `php artisan env:remove`
     * followed by the key of the environment variable to remove.
     *
     * @var string
     */
    protected $signature = 'env:remove {key}';

    /**
     * The console command description.
     *
     * This description provides a brief overview of the command's functionality
     * when listing available commands via `php artisan`.
     *
     * @var string
     */
    protected $description = 'Remove an environment variable';

    /**
     * Execute the console command.
     *
     * This method handles the logic for removing the specified environment variable
     * from the .env file. It reads the current .env file, removes the line containing
     * the specified key, and then writes the modified content back to the .env file.
     * If the .env file does not exist, an error message is displayed.
     */
    public function handle(): void
    {
        // Retrieve the key of the environment variable to remove
        $key = $this->argument('key');

        // Define the path to the .env file
        $path = base_path('.env');

        // Check if the .env file exists
        if (file_exists($path)) {
            // Read the .env file, remove the specified variable, and write the changes back
            file_put_contents($path, preg_replace(
                '/^' . preg_quote($key, '/') . '=.*$/m',
                '',
                file_get_contents($path)
            ));

            // Inform the user of the successful removal
            $this->info("Environment variable {$key} removed");
        } else {
            // Display an error message if the .env file does not exist
            $this->error('.env file does not exist.');
        }
    }
}
