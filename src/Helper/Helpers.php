<?php
namespace App\Helper;

class Helpers
{
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isStrongPassword(string $password): bool
    {
        // At least 8 chars, one letter, one number
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password);
    }

    public static function passwordsMatch(string $password, string $confirm): bool
    {
        return $password === $confirm;
    }

    public static function isFilled(string $value): bool
    {
        return trim($value) !== '';
    }
}
