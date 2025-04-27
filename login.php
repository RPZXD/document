require __DIR__ . '/vendor/autoload.php';

use App\DatabaseUsers;
use App\User;
use App\AuthController;

session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$userDb = new DatabaseUsers();
$user = new User($userDb);
$auth = new AuthController($user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->login($_POST['username'], $_POST['password']);
} else {
    $auth->showLogin();
}
