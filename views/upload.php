<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Document</title>
</head>
<body>
<form method="post" action="index.php?action=upload" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <label>เลือกไฟล์: <input type="file" name="document" required></label><br>
    <button type="submit">Upload</button>
</form>
<a href="index.php?action=logout">Logout</a>
</body>
</html>
