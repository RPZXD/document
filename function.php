<?php

// ฟังก์ชันแปลง role_edoc เป็นชื่อเต็ม
function getRoleEdocName($role_edoc) {
    $map = [
        'DIR' => 'Director (ผู้อำนวยการ)',
        'VP' => 'Vice Principal (รองผู้อำนวยการ)',
        'HOD' => 'Head of Department (หัวหน้าแผนก/กลุ่มสาระ)',
        'T' => 'Teacher (ครู)',
        'ADM' => 'Administrator'
    ];
    return isset($map[$role_edoc]) ? $map[$role_edoc] : $role_edoc;
}

// สร้างเมนูตาม role
function getMenuByRole($role) {
    $menus = [
        'ครู' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
        ],
        'เจ้าหน้าที่' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
            ['label' => 'อัปโหลด', 'href' => 'upload-documents.php'],
        ],
        'ผู้บริหาร' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
        ],
        'admin' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
            ['label' => 'อัปโหลด', 'href' => 'upload-documents.php'],
            ['label' => 'ตั้งค่า', 'href' => 'settings.php'],
            ['label' => 'จัดการผู้ใช้', 'href' => 'manage-users.php'],
            ['label' => 'รายงาน', 'href' => 'reports.php'],
        ],
    ];
    // เพิ่มออกจากระบบให้ทุก role
    $logout = ['label' => 'ออกจากระบบ', 'href' => 'logout.php', 'class' => 'text-red-300'];
    $roleMenus = isset($menus[$role]) ? $menus[$role] : [];
    $roleMenus[] = $logout;
    return $roleMenus;
}

// สร้างลิงก์รูปโปรไฟล์
$ImgProfileUser = '';
if (!empty($user['Teach_photo'])) {
    $ImgProfileUser = 'https://std.phichai.ac.th/teacher/uploads/phototeach/' . $user['Teach_photo'];
} else {
    $ImgProfileUser = '/document/src/logo/logo-phicha.png'; // fallback รูป default
}

$roleWelcome = [
    'ครู' => '👩‍🏫 ยินดีต้อนรับคุณครู',
    'เจ้าหน้าที่' => '🧑‍💼 ยินดีต้อนรับเจ้าหน้าที่',
    'ผู้บริหาร' => '👨‍💼 ยินดีต้อนรับผู้บริหาร',
    'admin' => '🛡️ Welcome, Administrator'
];
