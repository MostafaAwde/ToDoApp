<?php
// src/Helper/Session.php

namespace App\Helper;

class Session
{
    /**
     * Ensure session is started.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a value in session.
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Get a value from session, or default if not set.
     *
     * @param mixed $default
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a key exists in session.
     */
    public static function has(string $key): bool
    {
        self::start();
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Remove a key from session.
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the current session entirely.
     */
    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            // Unset all session variables
            $_SESSION = [];

            // Delete session cookie if any
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            session_destroy();
        }
    }

    /**
     * Flash data: if $message provided, set it; otherwise get and clear it.
     *
     * @param mixed $message
     */
    public static function flash(string $key, $message = null)
    {
        self::start();
        if ($message === null) {
            $value = $_SESSION['flash'][$key] ?? null;
            if (isset($_SESSION['flash'][$key])) {
                unset($_SESSION['flash'][$key]);
            }
            return $value;
        }

        $_SESSION['flash'][$key] = $message;
    }
}
