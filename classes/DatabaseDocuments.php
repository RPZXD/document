<?php
namespace App;

use PDO;
use PDOException;

class DatabaseDocuments
{
    private $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=phichaia_doc;charset=utf8mb4';
        $user = 'root';
        $pass = '';
        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            throw new \Exception('Document DB Connection failed');
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
