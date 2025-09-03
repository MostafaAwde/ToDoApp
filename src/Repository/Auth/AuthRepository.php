<?php
namespace App\Repository\Auth;

use App\Helper\Database;
use RuntimeException;

class AuthRepository implements IAuthRepository
{
    public function __construct(private Database $db) {}

    public function findByEmail(string $email): ?array
    {
        $row = $this->db->queryDB(
            'SELECT * FROM users WHERE email = :email LIMIT 1',
            Database::SELECTSINGLE,
            [[':email', $email]]
        );

        return $row ?: null;
    }

    public function insert(string $name, string $email, string $passwordHash): int
    {
        $sql = "CALL sp_insert_user(:p_name, :p_email, :p_password_hash)";

        $rows = $this->db->queryDB(
            $sql,
            Database::EXECUTE,
            [
                [':p_name', $name],
                [':p_email', $email],
                [':p_password_hash', $passwordHash],
            ]
        );

        // Your SP does: SELECT LAST_INSERT_ID() AS user_id;
        if (empty($rows) || !isset($rows[0]['user_id'])) {
            throw new \RuntimeException("Stored procedure did not return a user_id.");
        }

        return (int) $rows[0]['user_id'];
    }

    public function update(int $id, string $name, string $email): void
    {
        $this->db->queryDB(
            'CALL sp_update_user(:id, :name, :email)',
            Database::EXECUTE,
            [
                [':id',    $id],
                [':name',  $name],
                [':email', $email],
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->queryDB(
            'CALL sp_delete_user(:id)',
            Database::EXECUTE,
            [[ ':id', $id ]]
        );
    }
}
