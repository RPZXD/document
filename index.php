<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\DatabaseUsers;
use App\DatabaseDocuments;
use App\User;
use App\Document;
use App\DocumentUploader;
use App\DocumentController;

session_start();

$dbUsers = new DatabaseUsers();
$dbDocs = new DatabaseDocuments();
$user = new User($dbUsers);
$document = new Document($dbDocs);
$uploader = new DocumentUploader();
$docController = new DocumentController($document, $uploader);

$action = $_GET['action'] ?? '';

try {
    // จัดการ login
    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        echo 'Login functionality temporarily disabled due to missing AuthController.';
    }
    // จัดการ logout
    elseif ($action === 'logout') {
        echo 'Logout functionality temporarily disabled due to missing AuthController.';
    }
    // ถ้ายังไม่ login ให้แสดงฟอร์ม login
    elseif (!isset($_SESSION['user'])) {
        require __DIR__ . '/views/login.php';
        exit;
    }
    // อัปโหลดไฟล์
    elseif ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('Invalid CSRF token');
        }
        $docController->upload($_FILES['document'], $_SESSION['user']['id']);
        unset($_SESSION['csrf_token']); // ใช้ token ครั้งเดียว
        header('Location: index.php');
        exit;
    }
    // ลบไฟล์
    elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('Invalid CSRF token');
        }
        $docController->delete((int)$_POST['doc_id'], $_SESSION['user']['id']);
        unset($_SESSION['csrf_token']); // ใช้ token ครั้งเดียว
        header('Location: index.php');
        exit;
    }
    // แสดงฟอร์มอัปโหลด
    elseif ($action === 'upload') {
        require __DIR__ . '/views/upload.php';
        exit;
    }
    // แสดงรายการเอกสาร
    else {
        $documents = $document->getDocuments();
        require __DIR__ . '/views/documents.php';
        exit;
    }
} catch (Exception $e) {
    // แสดง error message แบบปลอดภัย
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}
