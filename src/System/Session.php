<?php
declare(strict_types=1);

namespace Pangio\Core\System;

/**
 * A static wrapper for managing PHP native sessions, providing a clean interface to start, access, and manipulate session data
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class Session {
    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Starts the session if it is not already active.
     *
     * @return void
     */
    public static function start(): void {
        if (PHP_SAPI === 'cli') {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Stores a value in the session.
     *
     * @param string $key Session key
     * @param mixed $value Value to store
     * @return void
     */
    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a value from the session.
     *
     * @param string $key Session key
     * @param mixed $default Default value if key does not exist
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Checks if a session key exists.
     *
     * @param string $key Session key
     * @return bool
     */
    public static function has(string $key): bool {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Removes a specific session key.
     *
     * @param string $key Session key
     * @return void
     */
    public static function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    /**
     * Clears all session data.
     *
     * @return void
     */
    public static function clear(): void {
        $_SESSION = [];
    }

    /**
     * Destroys the session completely.
     *
     * @return void
     */
    public static function destroy(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        }
    }

    /**
     * Stores a temporary flash message inside the session.
     *
     * @param string $type
     * @param string $message
     * @return void
     */
    public static function setFlashMessage(string $type, string $message): void {
        self::set('flashMessage', [
            'type' => esc($type),
            'message' => esc($message)
        ]);
    }

    /**
     * Retrieves a temporary flash message.
     *
     * @return array|null
     */
    public static function getFlashMessage(): ?array {
        $flashMessage = self::get('flashMessage');

        self::remove('flashMessage');

        return $flashMessage;
    }
}