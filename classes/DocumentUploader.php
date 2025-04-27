<?php
namespace App;

class DocumentUploader
{
    private $allowedTypes = [
        'application/pdf' => 'pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx'
    ];
    private $maxSize = 10485760; // 10MB

    // อัปโหลดไฟล์
    public function upload($file): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Upload error');
        }
        if ($file['size'] > $this->maxSize) {
            throw new \Exception('File too large');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!array_key_exists($mime, $this->allowedTypes)) {
            throw new \Exception('Invalid file type');
        }

        $ext = $this->allowedTypes[$mime];
        $newName = $this->generateFileName($ext);

        $uploadDir = __DIR__ . '/../uploads/';
        $dest = $uploadDir . $newName;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \Exception('Failed to move uploaded file');
        }

        chmod($dest, 0644);

        return [
            'filename' => $newName,
            'original_name' => $file['name']
        ];
    }

    // สร้างชื่อไฟล์ใหม่แบบสุ่ม
    private function generateFileName($ext): string
    {
        return uniqid('', true) . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    }
}
