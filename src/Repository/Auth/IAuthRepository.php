<?php
namespace App\Repository\Auth;

interface IAuthRepository
{
    public function findByEmail(string $email): ?array;
    public function insert(string $name, string $email, string $passwordHash): int;
    public function update(int $id, string $name, string $email): void;
    public function delete(int $id): void;
}
