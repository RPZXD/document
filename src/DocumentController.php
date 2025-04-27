<?php

namespace App;

class DocumentController
{
    protected $document;
    protected $uploader;

    public function __construct($document, $uploader)
    {
        $this->document = $document;
        $this->uploader = $uploader;
    }

    public function upload($file, $userId)
    {
        // Stub: implement upload logic here
    }

    public function delete($docId, $userId)
    {
        // Stub: implement delete logic here
    }
}
