<?php
// src/Dto/Auth/SignupDto.php
// in src/Dto/LoginDto.php
namespace App\Dto\Auth;

class SignupDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
