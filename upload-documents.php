<?php
session_start();
if (empty($_SESSION['logged_in']) || empty($_SESSION['role']) || empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$role = $_SESSION['role'];

// โหลด config
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
$pageConfig = $config['global'];


require_once 'function.php';
$menuItems = getMenuByRole($role);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <?php require_once 'header.php'; ?>
</head>
<body class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 min-h-screen flex flex-col">

    <!-- Navbar (เหมือน view-documents.php) -->
    <nav class="bg-gradient-to-r from-blue-700 to-purple-700 shadow text-white px-4 md:px-6 py-4 flex flex-row md:justify-between md:items-center gap-3 md:gap-0 relative">
        <div class="flex items-center space-x-3">
            <?php if (!empty($pageConfig['logoLink'])): ?>
                <img src="<?php echo htmlspecialchars($pageConfig['logoLink']); ?>" alt="logo" class="h-10 w-10 rounded-full bg-white p-1 shadow" />
            <?php else: ?>
                <span class="text-2xl">📄</span>
            <?php endif; ?>
            <span class="font-bold text-xl tracking-wide"><?php echo htmlspecialchars($pageConfig['nameschool']); ?></span>
        </div>
        <ul class="hidden md:flex flex-wrap justify-center md:justify-start space-x-4 md:space-x-6 text-base">
            <?php foreach ($menuItems as $item): ?>
                <li>
                    <a href="<?php echo htmlspecialchars($item['href']); ?>" class="hover:underline <?php echo isset($item['class']) ? $item['class'] : ''; ?>">
                        <?php echo htmlspecialchars($item['label']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="flex items-center space-x-2 justify-center md:justify-end mt-2 md:mt-0">
            <div class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-gradient-to-tr from-blue-300 to-purple-300 flex items-center justify-center text-xl md:text-2xl shadow overflow-hidden">
                <img src="<?php echo htmlspecialchars($ImgProfileUser); ?>" alt="profile" class="object-cover w-full h-full">
            </div>
            <div class="flex flex-col">
                <span class="font-semibold text-sm md:text-base"><?php echo htmlspecialchars($user['Teach_name']); ?></span>
                <span class="text-xs text-blue-100"><?php echo htmlspecialchars($role); ?> | <?php echo htmlspecialchars(getRoleEdocName($user['role_edoc'])); ?></span>
            </div>
        </div>
        <!-- Floating Mobile Menu Button (top-right) -->
        <div x-data="{ open: false }" class="md:hidden absolute top-4 right-4">
            <button @click="open = !open"
                class="bg-gradient-to-r from-blue-700 to-purple-700 text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center focus:outline-none transition hover:scale-110"
                aria-label="เมนู">
                <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <!-- Mobile Menu Drawer -->
            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 bg-gradient-to-r from-blue-700 to-purple-700 rounded-xl shadow-lg p-4 w-48 flex flex-col space-y-3 transition z-50"
                x-transition>
                <?php foreach ($menuItems as $item): ?>
                    <a href="<?php echo htmlspecialchars($item['href']); ?>" class="block text-white hover:underline <?php echo isset($item['class']) ? $item['class'] : ''; ?>">
                        <?php echo htmlspecialchars($item['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center py-8 px-2">
        <div class="w-full max-w-xl bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-6 flex items-center gap-2">📤 อัปโหลดเอกสารใหม่</h2>
            <form action="controllers/UploadController.php" method="POST" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <div>
                    <label class="block font-medium mb-1">ชื่อไฟล์เอกสาร <span class="text-red-500">*</span></label>
                    <input type="file" name="file" required class="block w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-medium mb-1">เลขที่เอกสาร <span class="text-red-500">*</span></label>
                    <input type="text" name="doc_num" required class="block w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-medium mb-1">หัวเรื่อง <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="block w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-medium mb-1">ฝ่ายงาน <span class="text-red-500">*</span></label>
                    <select name="group_type" required class="block w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
                        <option value="">-- เลือกฝ่ายงาน --</option>
                        <?php foreach ($groupMap as $k => $v): ?>
                            <option value="<?= $k ?>"><?= htmlspecialchars($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">ปี <span class="text-red-500">*</span></label>
                    <select name="pee" required class="block w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
                        <option value="">-- เลือกปี --</option>
                        <?php
                        $currentYear = (int)date('Y') + 543; // ปี พ.ศ.
                        for ($y = $currentYear; $y >= $currentYear - 3; $y--) {
                            echo '<option value="' . $y . '">' . $y . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">รายละเอียดเพิ่มเติม</label>
                    <textarea name="detail" rows="3" class="block w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition duration-300">อัปโหลด</button>
            </form>
        </div>
    </main>
    <!-- Footer (เหมือน view-documents.php) -->
   <?php require_once 'footer.php'; ?>
    <?php if (isset($_GET['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            text: <?= json_encode($_GET['success']) ?>,
            confirmButtonText: 'ปิด',
            confirmButtonColor: '#3085d6'
        });
    </script>
    <?php elseif (isset($_GET['error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: <?= json_encode($_GET['error']) ?>,
            confirmButtonText: 'ปิด',
            confirmButtonColor: '#d33'
        });
    </script>
    <?php endif; ?>
</body>
</html>
