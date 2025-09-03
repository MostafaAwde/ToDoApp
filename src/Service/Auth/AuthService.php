<?php
// src/Service/Auth/AuthService.php
namespace App\Service\Auth;

use App\Dto\Auth\SignupDto;
use App\Dto\Auth\LoginDto;
use App\Repository\Auth\IAuthRepository;

class AuthService implements IAuthService
{
    public function __construct(private IAuthRepository $repo) {}

    public function signup(SignupDto $dto): int
    {
        if ($this->repo->findByEmail($dto->email)) {
            throw new \Exception('Email already registered');
        }
        $passwordHash = password_hash($dto->password, PASSWORD_DEFAULT);
        return $this->repo->insert(
            $dto->name,
            $dto->email,
            $passwordHash
        );
    }

    public function login(LoginDto $dto): array
    {
        $user = $this->repo->findByEmail($dto->email);
        if (!$user || !password_verify($dto->password, $user['password_hash'])) {
            throw new \Exception('Invalid credentials');
        }
        return $user;
    }
}
