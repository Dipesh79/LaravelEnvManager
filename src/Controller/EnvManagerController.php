<?php

namespace Dipesh79\LaravelEnvManager\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

/**
 * Handles environment variable management within a Laravel application.
 *
 * Provides functionality for displaying, updating, and backing up environment variables
 * through a user-friendly interface. This controller ensures that only authorized users
 * can perform these actions, enhancing the application's security.
 */
class EnvManagerController extends Controller
{
    /**
     * Display the environment variables management page.
     *
     * Checks if the authenticated user has permission to access the environment variables
     * management page. If the user has access, the .env file's contents are read, parsed,
     * and passed to the view for display. If the user does not have access, a 403 Forbidden
     * response is returned.
     *
     * @return View The view for managing environment variables.
     */
    public function index(): View
    {
        // Check if the authenticated user has access to this page
        if (!auth()->user()->hasAccessToPage()) {
            abort(403); // Abort with a 403 Forbidden if the user lacks access
        }

        // Attempt to read the .env file content
        $envContent = File::exists(base_path('.env')) ? File::get(base_path('.env')) : '';
        // Parse the .env file content into an array
        $envVariables = $this->parseEnv($envContent);

        // Return the view with the parsed environment variables
        return view('envManager::index', compact('envVariables'));
    }

    /**
     * Parses the content of an .env file into an associative array.
     *
     * Takes the raw content of an .env file as a string and converts it into an associative
     * array where each key-value pair represents an environment variable. This method is
     * used internally to facilitate the display and update of environment variables.
     *
     * @param string $envContent The content of the .env file.
     * @return array An associative array of environment variables.
     */
    private function parseEnv(string $envContent): array
    {
        $lines = explode("\n", $envContent);
        $envVariables = [];

        foreach ($lines as $line) {
            if (str_contains($line, '=')) {
                list($key, $value) = explode('=', $line, 2);
                $envVariables[$key] = $value;
            }
        }
        return $envVariables;
    }

    /**
     * Updates the .env file with new or modified environment variables.
     *
     * Processes the incoming request to extract environment variables that need to be
     * updated or added. It then rebuilds the .env file's content with these changes and
     * saves the updated content back to the .env file. A redirect response is returned,
     * redirecting the user back to the environment variables management page with a
     * success message.
     *
     * @param Request $request The incoming request containing the environment variables to update.
     * @return RedirectResponse A redirect response back to the environment variables management page.
     */
    public function update(Request $request): RedirectResponse
    {
        // Retrieve existing and new variables from the request
        $variables = $request->input('variables', []);
        $newVariables = $request->input('newVariables', []);

        // Combine existing and new variables
        foreach ($newVariables as $newVariable) {
            list($key, $value) = explode('=', $newVariable, 2);
            $variables[$key] = $value;
        }

        // Build the new .env content from the combined variables
        $envContent = $this->buildEnvContent($variables);
        // Save the new .env content to the file
        File::put(base_path('.env'), $envContent);

        // Redirect back to the environment variables management page with a success message
        return redirect()->route('laravel-env-manager')->with('success', 'Environment variables updated successfully!');
    }

    /**
     * Builds the content of the .env file from an associative array of environment variables.
     *
     * Converts an associative array of environment variables into a string formatted as
     * an .env file's content. This method is used internally to generate the updated .env
     * file content after adding or modifying environment variables.
     *
     * @param array $variables An associative array of environment variables.
     * @return string The content for the .env file.
     */
    private function buildEnvContent($variables): string
    {
        $lines = [];
        foreach ($variables as $key => $value) {
            $lines[] = "{$key}={$value}";
        }
        return implode("\n", $lines);
    }

    /**
     * Creates a backup of the current .env file.
     *
     * Generates a backup of the .env file by copying it to a designated backup directory
     * within the storage path. Each backup file is timestamped to allow for easy identification
     * and restoration. This method provides a way to safeguard against accidental loss or
     * modification of critical environment variables.
     *
     * @return RedirectResponse A redirect response back to the environment variables management page with a success message.
     */
    public function backup(): RedirectResponse
    {
        $backupPath = storage_path('app/env_backups');
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFilename = ".env_{$timestamp}";

        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        File::copy(base_path('.env'), "{$backupPath}/{$backupFilename}");

        return back()->with('success', 'Backup created successfully.');
    }
}
