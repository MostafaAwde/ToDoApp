<?php
// src/Service/Auth/IAuthService.php
namespace App\Service\Auth;

use App\Dto\Auth\SignupDto;
use App\Dto\Auth\LoginDto;

interface IAuthService
{
    public function signup(SignupDto $dto): int;
    public function login(LoginDto $dto): array;
}
