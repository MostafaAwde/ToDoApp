<?php
// src/Dto/Auth/LoginDto.php
// in src/Dto/LoginDto.php
namespace App\Dto\Auth;

class LoginDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
