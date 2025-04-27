<?php
namespace App\Controllers;

use App\DocumentUploader;
use App\DatabaseDocuments;
use App\Document;

class UploadController
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseDocuments();
    }

    public function handleUpload()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception('Invalid request method.');
        }

        // ตรวจสอบ CSRF Token
        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            $_POST['csrf_token'] !== $_SESSION['csrf_token']
        ) {
            throw new \Exception('Invalid CSRF token.');
        }

        if (!isset($_FILES['document'])) {
            throw new \Exception('No file uploaded.');
        }

        $uploader = new DocumentUploader();
        $uploadInfo = $uploader->upload($_FILES['document']);

        // บันทึกข้อมูลลงฐานข้อมูล
        $sql = "INSERT INTO documents (name, path, upload_date) VALUES (:name, :path, NOW())";
        $params = [
            'name' => $uploadInfo['name'],
            'path' => $uploadInfo['path'],
        ];
        $this->db->execute($sql, $params);

        return true;
    }
}
