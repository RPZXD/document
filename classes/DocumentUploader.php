<?php
namespace App;

class DocumentUploader
{
    private $uploadDir;
    private $maxFileSize = 10485760; // 10MB

    public function __construct($uploadDir = __DIR__ . '/../uploads/')
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
    }

    public function upload($file)
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new \Exception('Invalid file upload.');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload error code: ' . $file['error']);
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new \Exception('File size exceeds 10MB limit.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            // เพิ่ม MIME type ที่ต้องการอนุญาต
        ];
        if (!in_array($mimeType, $allowedTypes, true)) {
            throw new \Exception('Invalid file type.');
        }

        // ตรวจสอบว่าเป็นไฟล์ที่อัปโหลดจริง
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Possible file upload attack.');
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uuid = $this->generateUuid();
        $newFileName = $uuid . ($ext ? '.' . strtolower($ext) : '');
        $destination = $this->uploadDir . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \Exception('Failed to move uploaded file.');
        }

        chmod($destination, 0644);

        return [
            'name' => $file['name'],
            'path' => $newFileName,
            'mime' => $mimeType,
            'size' => $file['size'],
        ];
    }

    private function generateUuid()
    {
        // Generate a version 4 (random) UUID
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
