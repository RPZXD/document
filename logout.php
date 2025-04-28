<?php
session_start();
if (isset($_GET['confirm']) && $_GET['confirm'] == '1') {
    session_unset();
    session_destroy();
    header('Location: login.php?logout=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="utils.js"></script>
</head>
<body>
<script>
    Swal.fire({
        title: 'ยืนยันการออกจากระบบ',
        text: 'คุณต้องการออกจากระบบหรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ออกจากระบบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php?confirm=1';
        } else {
            window.location.href = 'index.php';
        }
    });
</script>
</body>
</html>
