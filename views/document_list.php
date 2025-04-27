<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการเอกสาร</title>
</head>
<body>
    <h2>รายการเอกสารราชการ</h2>
    <a href="upload.php">อัปโหลดเอกสารใหม่</a> | <a href="logout.php">Logout</a>
    <table border="1">
        <tr>
            <th>ชื่อไฟล์ต้นฉบับ</th>
            <th>วันที่อัปโหลด</th>
            <th>โดย</th>
            <th>ดาวน์โหลด</th>
            <th>ลบ</th>
        </tr>
        <?php foreach ($documents as $doc): ?>
        <tr>
            <td><?php echo htmlspecialchars($doc['original_name']); ?></td>
            <td><?php echo htmlspecialchars($doc['uploaded_at']); ?></td>
            <td><?php echo htmlspecialchars($doc['user_id']); ?></td>
            <td><a href="download.php?id=<?php echo $doc['id']; ?>">ดาวน์โหลด</a></td>
            <td><a href="delete.php?id=<?php echo $doc['id']; ?>" onclick="return confirm('ยืนยันลบ?');">ลบ</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
