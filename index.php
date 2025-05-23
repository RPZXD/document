<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

// อ่าน config
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
$pageConfig = $config['global'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($pageConfig['logoLink']); ?>" />
    <title><?php echo htmlspecialchars($pageConfig['pageTitle']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Google Font: Mali -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mali:wght@200;300;400;500;600;700&display=swap">
    <style>
        /* เพิ่มลูกเล่น animation */
        .fade-in {
            animation: fadeIn 1.2s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .bounce {
            animation: bounce 1s infinite alternate;
        }
        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }
        .gradient-bg {
            background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
        }
        .glow:hover {
            box-shadow: 0 0 20px 4px #facc15;
            transition: box-shadow 0.3s;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal" style="font-family: 'Mali', sans-serif;">

    <!-- Navbar -->
    <nav class="gradient-bg py-4 shadow-lg fade-in">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="/" class="flex items-center text-white text-2xl font-bold tracking-wider hover:scale-105 transition-transform duration-200">
                <?php if (!empty($pageConfig['logoLink'])): ?>
                    <img src="<?php echo htmlspecialchars($pageConfig['logoLink']); ?>" alt="logo" class="h-10 w-10 mr-2 rounded-full bg-white p-1 shadow" />
                <?php endif; ?>
                <?php echo htmlspecialchars($pageConfig['nameschool']); ?>
            </a>
            <div>
                <a href="login.php" class="text-white px-4 py-2 hover:bg-blue-700 rounded transition duration-200">เข้าสู่ระบบ</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white h-screen flex flex-col justify-center items-center text-center p-6 fade-in">
        <h1 class="text-5xl font-bold mb-4 bounce"><?php echo htmlspecialchars($pageConfig['pageTitle']); ?></h1>
        <p class="text-xl mb-8">แอปพลิเคชันที่ช่วยให้การอัปโหลดเอกสารง่าย และปลอดภัยยิ่งขึ้น</p>
        <a href="login.php" class="bg-yellow-500 text-blue-600 px-6 py-3 rounded-lg text-xl font-semibold hover:bg-yellow-400 glow transition duration-200 shadow-lg animate-pulse">เริ่มใช้งานทันที 🚀</a>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white fade-in" id="features">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold mb-8">คุณสมบัติของระบบ 📄</h2>
            <div class="grid md:grid-cols-3 grid-cols-1 gap-8">
                <div class="bg-gray-200 p-8 rounded-lg shadow-lg hover:scale-105 transition-transform duration-300">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-4">ความปลอดภัยสูงสุด</h3>
                    <p>ระบบมีการป้องกันไฟล์ปลอมและรักษาความปลอดภัยในการอัปโหลดข้อมูล</p>
                </div>
                <div class="bg-gray-200 p-8 rounded-lg shadow-lg hover:scale-105 transition-transform duration-300">
                    <div class="text-4xl mb-4">📈</div>
                    <h3 class="text-xl font-semibold mb-4">ติดตามผลได้ง่าย</h3>
                    <p>ตรวจสอบสถานะการอัปโหลดเอกสาร และประวัติการใช้งานได้อย่างรวดเร็ว</p>
                </div>
                <div class="bg-gray-200 p-8 rounded-lg shadow-lg hover:scale-105 transition-transform duration-300">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-semibold mb-4">ใช้งานง่าย</h3>
                    <p>ระบบออกแบบให้ใช้งานง่ายและสะดวกไม่ยุ่งยาก เพียงไม่กี่คลิกก็สามารถอัปโหลดเอกสารได้</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once 'footer.php'; ?>

</body>
</html>
