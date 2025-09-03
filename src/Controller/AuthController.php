<?php
// src/Controller/AuthController.php

namespace App\Controller;

use App\Dto\Auth\SignupDto;
use App\Dto\Auth\LoginDto;
use App\Service\Auth\IAuthService;
use App\Helper\Helpers;
use App\Helper\Session;
use Exception;

class AuthController
{
    public function __construct(private IAuthService $service) {}

    public function showSignup(): void
    {
        $title   = 'Sign Up';
        $content = __DIR__ . '/../../public/views/signup.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function signup(): void
    {
        Session::start();

        $name     = $_POST['name']     ?? '';
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['passwordConfirmation'] ?? '';

        // 1) Basic validation
        if (!Helpers::isFilled($name, $email, $password, $confirm)) {
            Session::flash('error', 'All fields are required.');
            header('Location: /signup');
            return;
        }
        if (!Helpers::isValidEmail($email)) {
            Session::flash('error', 'Invalid email address.');
            header('Location: /signup');
            return;
        }
        if (!Helpers::isStrongPassword($password)) {
            Session::flash('error', 'Password must be at least 8 characters and include a number.');
            header('Location: /signup');
            return;
        }
        if (!Helpers::passwordsMatch($password, $confirm)) {
            Session::flash('error', 'Passwords do not match.');
            header('Location: /signup');
            return;
        }

        // 2) Attempt create user
        try {
            $dto    = new SignupDto($name, $email, $password);
            $userId = $this->service->signup($dto);

            Session::set('user_id', $userId);
            header('Location: /dashboard');
        } catch (Exception $e) {
            // flashes "Email already registered" or any other signup exception
            Session::flash('error', $e->getMessage());
            header('Location: /signup');
        }
    }

    public function showLogin(): void
    {
        $title   = 'Log In';
        $content = __DIR__ . '/../../public/views/login.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function login(): void
    {
        Session::start();

        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        // 1) Basic required‐field check
        if (!Helpers::isFilled($email, $password)) {
            Session::flash('error', 'Email and password are required.');
            header('Location: /login');
            return;
        }

        // 2) Attempt authentication
        try {
            $dto  = new LoginDto($email, $password);
            $user = $this->service->login($dto);

            Session::set('user_id',   (int) $user['id']);
            Session::set('user_name', $user['name']);
            header('Location: /dashboard');
        } catch (Exception $e) {
            // flashes "Invalid credentials" or any login exception
            Session::flash('error', $e->getMessage());
            header('Location: /login');
        }
    }

    public function logout(): void
    {
        Session::start();
        Session::destroy();
        header('Location: /login');
    }
}
