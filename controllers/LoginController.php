<?php

require_once __DIR__ . '/../models/User.php';

class LoginController
{
    public function login($username, $password, $role)
    {
        $user = User::authenticate($username, $password, $role);
        if ($user === 'change_password') {
            // redirect ไปหน้าเปลี่ยนรหัสผ่าน
            $_SESSION['change_password_user'] = $username;
            header('Location: change_password.php');
            exit;
        }
        if ($user) {
            $_SESSION['logged_in'] = true;
            $_SESSION['role'] = $role;
            $_SESSION['user'] = [
                'Teach_id' => $user['Teach_id'],
                'Teach_name' => $user['Teach_name'],
                'role_edoc' => $user['role_edoc'],
                'Teach_photo' => $user['Teach_photo'], // เพิ่มบรรทัดนี้
            ];
            header('Location: dashboard.php');
            exit;
        } else {
            return "ชื่อผู้ใช้, รหัสผ่าน หรือบทบาทไม่ถูกต้อง 🚫";
        }
    }
}
