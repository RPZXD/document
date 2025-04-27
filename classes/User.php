<?php
namespace App;

use PDO;

class User
{
    private $db;
    private $userData;

    public function __construct(DatabaseUsers $db)
    {
        $this->db = $db->getConnection();
    }

    // ตรวจสอบการ login
    public function authenticate(string $username, string $password): bool
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $this->userData = $user;
            return true;
        }
        return false;
    }

    // ดึงข้อมูลผู้ใช้
    public function getUserData(): ?array
    {
        return $this->userData;
    }
}
