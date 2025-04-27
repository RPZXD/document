require __DIR__ . '/vendor/autoload.php';

use App\DatabaseDocuments;
use App\Document;
use App\DocumentUploader;
use App\AuthController;
use App\DocumentController;

session_start();
AuthController::checkAuth();

$docDb = new DatabaseDocuments();
$document = new Document($docDb);
$uploader = new DocumentUploader();
$controller = new DocumentController($document, $uploader);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF validation failed');
    }
    $controller->upload($_FILES['document'], $_SESSION['user_id']);
} else {
    $controller->showUploadForm();
}
