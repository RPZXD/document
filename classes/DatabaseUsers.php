<?php
namespace App;

use PDO;
use PDOException;

class DatabaseUsers
{
    private $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=phichaia_student;charset=utf8mb4';
        $user = 'root';
        $pass = '';
        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            throw new \Exception('User DB Connection failed');
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
