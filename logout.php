<?php
session_start();
session_unset();
session_destroy();
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
        icon: 'success',
        title: 'ออกจากระบบเรียบร้อยแล้ว',
        text: 'คุณได้ออกจากระบบสำเร็จ',
        confirmButtonText: 'ตกลง',
        timer: 1500,
        willClose: () => {
            window.location.href = 'login.php?logout=1';
        }
    }).then(() => {
        window.location.href = 'login.php?logout=1';
    });
</script>
</body>
</html>
