<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Documents</title>
</head>
<body>
<h2>รายการเอกสาร</h2>
<table border="1">
    <tr>
        <th>ชื่อไฟล์ต้นฉบับ</th>
        <th>วันที่อัปโหลด</th>
        <th>โดย</th>
        <th>ลบ</th>
    </tr>
    <?php foreach ($documents as $doc): ?>
    <tr>
        <td><?php echo htmlspecialchars($doc['original_name']); ?></td>
        <td><?php echo htmlspecialchars($doc['uploaded_at']); ?></td>
        <td><?php echo htmlspecialchars($doc['user_id']); ?></td>
        <td>
            <form method="post" action="index.php?action=delete" onsubmit="return confirm('ยืนยันลบ?');">
                <input type="hidden" name="doc_id" value="<?php echo (int)$doc['id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <button type="submit">ลบ</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<a href="upload.php">อัปโหลดเอกสาร</a> | <a href="index.php?action=logout">Logout</a>
</body>
</html>
