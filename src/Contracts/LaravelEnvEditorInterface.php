<?php

namespace Dipesh79\LaravelEnvManager\Contracts;

/**
 * Interface LaravelEnvEditorInterface
 *
 * Defines the contract for checking user access to the Laravel environment editor page.
 * Implementing this interface ensures that a class provides a method to determine
 * if a user has the necessary permissions to access the environment variables editor.
 */
interface LaravelEnvEditorInterface
{
    /**
     * Determine if the user has access to the environment editor page.
     *
     * @return bool True if the user has access, false otherwise.
     */
    public function hasAccessToPage(): bool;
}
