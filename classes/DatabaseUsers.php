<?php
namespace App;

use PDO;
use PDOException;

class DatabaseUsers
{
    private $pdo;

    public function __construct(
        $host = 'localhost',
        $dbname = 'phichaia_student',
        // $username = 'root',
        // $password = ''
        $username = 'phichaia_stdcare',
        $password = '4w87!9lZp'
    ) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    // เพิ่มเมธอดนี้
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception('Database query error: ' . $e->getMessage());
        }
    }

    public function getTeacherByUsername($username)
    {
        $sql = "SELECT * FROM teacher WHERE Teach_id = :username OR Teach_name = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
}
