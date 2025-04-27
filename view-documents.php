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

// สร้างลิงก์รูปโปรไฟล์
$ImgProfileUser = '';
if (!empty($user['Teach_photo'])) {
    $ImgProfileUser = 'https://std.phichai.ac.th/teacher/uploads/phototeach/' . $user['Teach_photo'];
} else {
    $ImgProfileUser = '/document/src/logo/logo-phicha.png'; // fallback รูป default
}

$roleWelcome = [
    'ครู' => '👩‍🏫 ยินดีต้อนรับคุณครู',
    'เจ้าหน้าที่' => '🧑‍💼 ยินดีต้อนรับเจ้าหน้าที่',
    'ผู้บริหาร' => '👨‍💼 ยินดีต้อนรับผู้บริหาร',
    'admin' => '🛡️ Welcome, Administrator'
];

// ฟังก์ชันแปลง role_edoc เป็นชื่อเต็ม
function getRoleEdocName($role_edoc) {
    $map = [
        'DIR' => 'Director (ผู้อำนวยการ)',
        'VP' => 'Vice Principal (รองผู้อำนวยการ)',
        'HOD' => 'Head of Department (หัวหน้าแผนก/กลุ่มสาระ)',
        'T' => 'Teacher (ครู)',
        'ADM' => 'Administrator'
    ];
    return isset($map[$role_edoc]) ? $map[$role_edoc] : $role_edoc;
}

// สร้างเมนูตาม role
function getMenuByRole($role) {
    $menus = [
        'ครู' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
        ],
        'เจ้าหน้าที่' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
            ['label' => 'อัพโหลด', 'href' => 'upload-documents.php'],
        ],
        'ผู้บริหาร' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
        ],
        'admin' => [
            ['label' => 'หน้าหลัก', 'href' => 'dashboard.php'],
            ['label' => 'ดูเอกสาร', 'href' => 'view-documents.php'],
            ['label' => 'อัพโหลด', 'href' => 'upload-documents.php'],
            ['label' => 'ตั้งค่า', 'href' => 'settings.php'],
            ['label' => 'จัดการผู้ใช้', 'href' => 'manage-users.php'],
            ['label' => 'รายงาน', 'href' => 'reports.php'],
        ],
    ];
    // เพิ่มออกจากระบบให้ทุก role
    $logout = ['label' => 'ออกจากระบบ', 'href' => 'logout.php', 'class' => 'text-red-300'];
    $roleMenus = isset($menus[$role]) ? $menus[$role] : [];
    $roleMenus[] = $logout;
    return $roleMenus;
}

$menuItems = getMenuByRole($role);

// โหลด autoload ถ้ายังไม่มี
require_once __DIR__ . '/classes/DatabaseDocuments.php';
require_once __DIR__ . '/models/document.php';
require_once __DIR__ . '/controllers/ViewController.php';

use App\Controllers\ViewController;

// ดึงข้อมูลเอกสาร
$controller = new ViewController();
$documents = $controller->index();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | <?php echo htmlspecialchars($pageConfig['nameschool']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mali:wght@200;300;400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Mali', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 min-h-screen flex flex-col">

    <!-- Navbar -->
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
    <main class="flex-1 flex flex-col items-center justify-center py-6 md:py-10 px-2">
        <div class="w-full max-w-5xl bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-extrabold mb-6 text-blue-700 flex items-center gap-2">
                📑 รายการเอกสาร
            </h2>
            <!-- Year Filter -->
            <div class="mb-6 flex flex-wrap items-center gap-3 bg-gradient-to-r from-blue-100 to-purple-100 rounded-lg px-4 py-3 shadow">
                <label for="yearFilter" class="font-semibold text-blue-700 flex items-center gap-1">
                    🗓️ ปี:
                </label>
                <select id="yearFilter" class="border border-blue-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none transition text-blue-700 font-medium bg-white shadow">
                    <option value="">ทั้งหมด</option>
                </select>
            </div>
            <div class="overflow-x-auto rounded-lg shadow">
                <table id="documentsTable" class="min-w-full table-auto border border-gray-200 rounded-lg overflow-hidden bg-white">
                    <thead class="bg-gradient-to-r from-blue-200 to-purple-200">
                        <tr>
                            <th class="px-3 py-3 border text-blue-800 font-bold text-center">#️⃣</th>
                            <th class="px-3 py-3 border text-blue-800 font-bold text-center">🆔 เลขที่เอกสาร</th>
                            <th class="px-3 py-3 border text-blue-800 font-bold text-center">📄 ชื่อไฟล์</th>
                            <th class="px-3 py-3 border text-blue-800 font-bold text-center">📝 หัวเรื่อง</th>
                            <th class="px-3 py-3 border text-blue-800 font-bold text-center">📅 วันที่อัพโหลด</th>
                            <th class="px-3 py-3 border text-blue-800 font-bold text-center">👤 โดย</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-700 to-purple-700 text-white text-center py-3 mt-10 shadow-inner text-xs md:text-base">
        <div class="flex flex-col items-center">
            <span class="text-base md:text-lg">© <?=date('Y')?> <?php echo htmlspecialchars($pageConfig['nameschool']); ?> | <?php echo htmlspecialchars($pageConfig['footerCredit']); ?> 🚀</span>
            <span class="mt-1">ติดต่อผู้ดูแลระบบ: line:tiwarpz</span>
        </div>
    </footer>
<!-- เพิ่ม DataTables CDN และ JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // สร้าง DataTable
    var table = $('#documentsTable').DataTable({
        ajax: 'api/documents.php',
        columns: [
            { 
                data: null,
                render: function (data, type, row, meta) {
                    return `<span class="font-bold text-blue-700">${meta.row + 1}</span>`;
                },
                className: "text-center"
            },
            { 
                data: 'doc_num',
                render: function(data) {
                    return `<span class="text-blue-900 font-semibold">${data}</span>`;
                },
                className: "text-center"
            },
            { 
                data: 'file_name',
                render: function(data, type, row) {
                    return `<a href="uploads/${encodeURIComponent(data)}" class="text-blue-600 underline font-medium hover:text-purple-600 transition" target="_blank">📎 ${data}</a>`;
                }
            },
            { 
                data: 'title',
                render: function(data) {
                    return `<span class="text-gray-800">${data}</span>`;
                }
            },
            { 
                data: 'date_upload',
                render: function(data) {
                    return `<span class="text-purple-700">${data}</span>`;
                },
                className: "text-center"
            },
            { 
                data: 'Teach_name',
                render: function(data) {
                    return `<span class="text-green-700 font-medium">${data ? data : '-'}</span>`;
                },
                className: "text-center"
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json'
        },
        initComplete: function(settings, json) {
            // สร้างปีใน dropdown จากข้อมูล pee
            var years = {};
            if (json && json.data) {
                json.data.forEach(function(row) {
                    if (row.pee && row.pee !== "0" && row.pee !== null) years[row.pee] = true;
                });
            }
            var $yearFilter = $('#yearFilter');
            Object.keys(years).sort().reverse().forEach(function(y) {
                $yearFilter.append(`<option value="${y}">${y}</option>`);
            });
        }
    });

    // filter ปี
    $('#yearFilter').on('change', function() {
        table.draw();
    });

    // custom filter function for pee (ปี)
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex, rowData) {
        var selectedYear = $('#yearFilter').val();
        if (!selectedYear) return true;
        if (rowData && rowData.pee) {
            return rowData.pee == selectedYear;
        }
        return true;
    });
});
</script>
</body>
</html>
