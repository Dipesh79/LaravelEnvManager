<?php

namespace Dipesh79\LaravelEnvManager\Console\Commands;

use Illuminate\Console\Command;

/**
 * A console command to set or update an environment variable in the .env file.
 *
 * This command allows for setting a new environment variable or updating an existing one
 * by specifying a key and value. It is useful for dynamically managing application settings
 * without directly editing the .env file. If the .env file does not exist, an error message
 * is displayed.
 */
class SetEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * This signature specifies that the command will be called as `php artisan env:set`
     * followed by the key and value of the environment variable to set or update.
     *
     * @var string
     */
    protected $signature = 'env:set {key} {value}';

    /**
     * The console command description.
     *
     * This description provides a brief overview of the command's functionality
     * when listing available commands via `php artisan`.
     *
     * @var string
     */
    protected $description = 'Set an environment variable';

    /**
     * Execute the console command.
     *
     * This method handles the logic for setting or updating the specified environment variable
     * in the .env file. It reads the current .env file, updates the value for the specified key,
     * and then writes the modified content back to the .env file. If the .env file does not exist,
     * an error message is displayed.
     */
    public function handle(): void
    {
        // Retrieve the key and value of the environment variable to set or update
        $key = $this->argument('key');
        $value = $this->argument('value');

        // Define the path to the .env file
        $path = base_path('.env');

        // Check if the .env file exists
        if (file_exists($path)) {
            // Update the .env file with the new value for the specified key
            file_put_contents($path, str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents($path)
            ));

            // Inform the user of the successful update
            $this->info("Environment variable {$key} set to {$value}");
        } else {
            // Display an error message if the .env file does not exist
            $this->error('.env file does not exist.');
        }
    }
}
