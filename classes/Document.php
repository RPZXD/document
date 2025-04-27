<?php
namespace App;

use PDO;

class Document
{
    private $db;

    public function __construct(DatabaseDocuments $db)
    {
        $this->db = $db->getConnection();
    }

    // เพิ่มข้อมูลเอกสาร
    public function addDocument($filename, $originalName, $userId)
    {
        $stmt = $this->db->prepare('INSERT INTO documents (filename, original_name, user_id, uploaded_at) VALUES (:filename, :original_name, :user_id, NOW())');
        $stmt->execute([
            ':filename' => $filename,
            ':original_name' => $originalName,
            ':user_id' => $userId
        ]);
    }

    // ดึงรายการเอกสาร
    public function getDocuments()
    {
        $stmt = $this->db->query('SELECT * FROM documents ORDER BY uploaded_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ลบข้อมูลเอกสาร
    public function deleteDocument($id)
    {
        $stmt = $this->db->prepare('DELETE FROM documents WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    // ดึงข้อมูลเอกสารตาม id
    public function getDocumentById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM documents WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
