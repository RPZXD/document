require __DIR__ . '/vendor/autoload.php';

use App\DatabaseUsers;
use App\User;
use App\AuthController;

session_start();
$userDb = new DatabaseUsers();
$user = new User($userDb);
$auth = new AuthController($user);
$auth->logout();
