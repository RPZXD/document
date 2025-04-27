<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Upload Document</title>
</head>
<body>
    <h2>อัปโหลดเอกสารราชการ</h2>
    <?php if (!empty($error)) echo '<div style="color:red;">' . htmlspecialchars($error) . '</div>'; ?>
    <form method="post" enctype="multipart/form-data" action="upload.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label>เลือกไฟล์: <input type="file" name="document" required></label><br>
        <button type="submit">อัปโหลด</button>
    </form>
    <a href="index.php">กลับหน้าหลัก</a>
</body>
</html>
