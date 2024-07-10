<?php

namespace Dipesh79\LaravelEnvManager\Console\Commands;

use Illuminate\Console\Command;

/**
 * A console command to list all environment variables from the .env file.
 *
 * This command provides a simple way to view all the current environment
 * variables set in the Laravel application's .env file. It's useful for
 * debugging and ensuring that your environment is configured correctly.
 */
class ListEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * This property defines how the command can be called from the CLI.
     * In this case, `php artisan env:list` will trigger this command.
     *
     * @var string
     */
    protected $signature = 'env:list';

    /**
     * The console command description.
     *
     * This description will appear when running `php artisan list`, providing
     * users with information about what the command does.
     *
     * @var string
     */
    protected $description = 'List all environment variables';

    /**
     * Execute the console command.
     *
     * This method reads the .env file from the base path of the Laravel
     * application and prints its content. If the .env file does not exist,
     * it will print an error message.
     */
    public function handle(): void
    {
        $path = base_path('.env'); // Define the path to the .env file
        if (file_exists($path)) { // Check if the .env file exists
            $envContent = file_get_contents($path); // Read the content of the .env file
            $this->info($envContent); // Print the content of the .env file
        } else {
            $this->error('.env file does not exist.'); // Print an error message if the .env file does not exist
        }
    }
}
