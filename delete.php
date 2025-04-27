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

if (isset($_GET['id'])) {
    $controller->delete((int)$_GET['id']);
} else {
    header('Location: index.php');
    exit;
}
