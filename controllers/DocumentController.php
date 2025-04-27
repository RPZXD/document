<?php
namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

class DocumentController
{
    private $document;
    private $uploader;

    public function __construct(Document $document, DocumentUploader $uploader)
    {
        $this->document = $document;
        $this->uploader = $uploader;
    }

    // อัปโหลดเอกสาร
    public function upload($file, $userId)
    {
        $result = $this->uploader->upload($file);
        $this->document->addDocument($result['filename'], $result['original_name'], $userId);
        // log การอัปโหลด
        error_log("User $userId uploaded file: " . $result['filename']);
    }

    // ลบเอกสาร
    public function delete($docId, $userId)
    {
        $doc = $this->document->getDocumentById($docId);
        if (!$doc) {
            throw new \Exception('Document not found');
        }
        $filePath = __DIR__ . '/../uploads/' . $doc['filename'];
        if (is_file($filePath)) {
            unlink($filePath);
            $this->document->deleteDocument($docId);
            // log การลบ
            error_log("User $userId deleted file: " . $doc['filename']);
        } else {
            throw new \Exception('File not found');
        }
    }
}
