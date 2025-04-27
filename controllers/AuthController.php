<?php
namespace App;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

class AuthController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Login
    public function login($username, $password)
    {
        if ($this->user->authenticate($username, $password)) {
            $_SESSION['user'] = $this->user->getUserData();
            return true;
        }
        return false;
    }

    // Logout
    public function logout()
    {
        session_destroy();
    }

    // ตรวจสอบ session
    public static function check()
    {
        return isset($_SESSION['user']);
    }
}
