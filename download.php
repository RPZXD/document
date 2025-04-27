require __DIR__ . '/vendor/autoload.php';

use App\DatabaseDocuments;
use App\Document;
use App\DocumentUploader;
use App\AuthController;

session_start();
AuthController::checkAuth();

$docDb = new DatabaseDocuments();
$document = new Document($docDb);
$uploader = new DocumentUploader();

if (isset($_GET['id'])) {
    $doc = $document->getDocumentById((int)$_GET['id']);
    if ($doc && $uploader->isValidUploadPath($doc['filename'])) {
        $file = __DIR__ . '/uploads/' . $doc['filename'];
        if (file_exists($file)) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file);
            $allowedTypes = [
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
            if (!in_array($mimeType, $allowedTypes, true)) {
                header('HTTP/1.1 403 Forbidden');
                exit('Forbidden file type.');
            }
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . htmlspecialchars($doc['original_name']) . '"');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }
}
header('Location: index.php');
exit;
