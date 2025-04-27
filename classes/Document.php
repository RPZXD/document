<?php
namespace App;

class Document
{
    public $id;
    public $name;
    public $path;
    public $upload_date;

    public function __construct($id, $name, $path, $upload_date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->path = $path;
        $this->upload_date = $upload_date;
    }
}
