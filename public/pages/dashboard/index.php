<?php
require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/helpers.php';
require_once __DIR__ . '/../../../app/ResponsiveDataService.php';

// Fallback function definitions in case helpers.php is not loaded
if (!function_exists('get_device_type')) {
    function get_device_type() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (preg_match('/(Mobile|Android|iPhone|iPod|iPad|BlackBerry)/i', $userAgent)) {
            return (preg_match('/(Tablet|iPad)/i', $userAgent)) ? 'tablet' : 'mobile';
        }
        return 'desktop';
    }
}

if (!function_exists('format_name_title_case')) {
    function format_name_title_case($name) {
        return ucwords(strtolower(trim($name)));
    }
}

if (!function_exists('truncate_text')) {
    function truncate_text($text, $max_length = 20, $suffix = '...') {
        $text = trim($text);
        if (strlen($text) <= $max_length) {
            return $text;
        }
        return substr($text, 0, $max_length - strlen($suffix)) . $suffix;
    }
}

// Use Auth class for authentication
$auth = new Auth();
if (!$auth->check()) {
    header('Location: /ksp_mono/public/login.php');
    exit;
}
$user = $auth->user() ?: [];

// Set item limits based on device type
$itemLimits = [
    'mobile' => [
        'transactions' => 5,
        'notifications' => 3
    ],
    'tablet' => [
        'transactions' => 8,
        'notifications' => 5
    ],
    'desktop' => [
        'transactions' => 15,
        'notifications' => 10
    ]
];

$deviceType = get_device_type();
$limits = $itemLimits[$deviceType];

// Load data with device-specific limits
$transactions = ResponsiveDataService::getData('log_audit', [
    'order' => 'created_at DESC'
]);

// Get dashboard metrics (match schema: simpanan_transaksi uses nilai & transaction_type)
$db = Database::conn();
$metrics = [
    'total_members' => $db->query("SELECT COUNT(*) AS count FROM anggota")->fetch()['count'] ?? 0,
    // Total setoran (deposit) dikurangi penarikan untuk saldo agregat
    'total_savings' => $db->query("SELECT COALESCE(SUM(CASE WHEN transaction_type = 'deposit' THEN nilai WHEN transaction_type = 'withdraw' THEN -nilai ELSE 0 END),0) AS total FROM simpanan_transaksi")->fetch()['total'] ?? 0,
    'total_loans' => $db->query("SELECT COALESCE(SUM(amount),0) AS total FROM pinjaman WHERE status = 'active'")->fetch()['total'] ?? 0,
    // Jika SHU dicatat sebagai transaksi simpanan dengan type_id khusus, sesuaikan query ini; default 0
    'total_shu' => 0
];
?>

<div class="container py-4">
    <!-- Dashboard Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                        <i class="bi bi-people fs-1 text-primary"></i>
                    </div>
                    <h3 class="h4 mb-1"><?php echo number_format($metrics['total_members']); ?></h3>
                    <p class="text-muted small mb-0">Total Anggota</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                        <i class="bi bi-cash-coin fs-1 text-success"></i>
                    </div>
                    <h3 class="h4 mb-1">Rp <?php echo number_format($metrics['total_savings'], 0, ',', '.'); ?></h3>
                    <p class="text-muted small mb-0">Total Simpanan</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                        <i class="bi bi-credit-card fs-1 text-warning"></i>
                    </div>
                    <h3 class="h4 mb-1">Rp <?php echo number_format($metrics['total_loans'], 0, ',', '.'); ?></h3>
                    <p class="text-muted small mb-0">Total Pinjaman</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                        <i class="bi bi-trophy fs-1 text-info"></i>
                    </div>
                    <h3 class="h4 mb-1">Rp <?php echo number_format($metrics['total_shu'], 0, ',', '.'); ?></h3>
                    <p class="text-muted small mb-0">Total SHU</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
            <div class="sidebar bg-light p-3 rounded shadow-sm">
                <h5 class="sidebar-title mb-3">Menu Cepat</h5>
                <div class="d-grid gap-2">
                    <?php if (isset($_SESSION['accessible_modules']) && is_array($_SESSION['accessible_modules'])): ?>
                    <?php foreach ($_SESSION['accessible_modules'] as $module): ?>
                    <?php if ($module['nama'] !== 'dashboard'): ?>
                    <a href="?modul=<?php echo $module['nama']; ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi <?php echo $module['ikon']; ?> me-2"></i><?php echo $module['nama_tampil']; ?>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Panel Aktivitas Utama -->
        <div class="col-lg-9 col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-0">Aktivitas Utama</h5>
                            <p class="text-muted small mb-0">Kelola data dan fitur aplikasi</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item active" href="#">Semua</a></li>
                                <li><a class="dropdown-item" href="#">Favorit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Sering Digunakan</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="row g-3">
                        <?php if (isset($_SESSION['accessible_modules']) && is_array($_SESSION['accessible_modules'])): ?>
                        <?php foreach ($_SESSION['accessible_modules'] as $module): ?>
                        <?php if ($module['nama'] !== 'dashboard'): ?>
                        <div class="col-xl-4 col-md-6">
                            <a href="?modul=<?php echo $module['nama']; ?>" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi <?php echo $module['ikon']; ?> fs-1 text-primary"></i>
                                        </div>
                                        <h5 class="h6 mb-1"><?php echo $module['nama_tampil']; ?></h5>
                                        <p class="text-muted small mb-0">Kelola <?php echo strtolower($module['nama_tampil']); ?></p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-arrow-right-short"></i> Masuk
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Kartu Bantuan -->
                        <div class="col-xl-4 col-md-6">
                            <a href="#" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-purple bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi bi-question-circle fs-1 text-purple"></i>
                                        </div>
                                        <h5 class="h6 mb-1">Bantuan</h5>
                                        <p class="text-muted small mb-0">Panduan penggunaan</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-purple bg-opacity-10 text-purple">
                                            <i class="bi bi-arrow-right-short"></i> Segera
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Aktivitas Terkini</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Keterangan</th>
                                    <th>Oleh</th>
                                </tr>
                            </thead>
                            <tbody id="transactions-table-body">
                                <?php foreach ($transactions as $transaction) { ?>
                                <tr>
                                    <td class="text-nowrap"><?php echo format_date($transaction['created_at']); ?></td>
                                    <td><?php echo $transaction['action']; ?></td>
                                    <td><?php echo $transaction['table_name'] . ' ID: ' . $transaction['record_id']; ?></td>
                                    <td class="text-nowrap"><?php echo $transaction['user_id']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let loading = false;
    let offset = <?php echo $limits['transactions']; ?>;

    function get_device_type() {
        const width = window.innerWidth;
        if (width < 768) return 'mobile';
        if (width < 1024) return 'tablet';
        return 'desktop';
    }

    function highlightActiveNav() {
        // Function to highlight active navigation - already handled in PHP
    }

    async function loadMoreTransactions() {
        if (loading) return;
        loading = true;
        
        try {
            const response = await fetch(`/api/load_more.php?offset=${offset}`);
            const data = await response.json();
            
            if (data.success && data.data.length) {
                const tableBody = document.getElementById('transactions-table-body');
                data.data.forEach(transaction => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-nowrap">${transaction.date}</td>
                        <td>${transaction.activity}</td>
                        <td>${transaction.description}</td>
                        <td class="text-nowrap">${transaction.user}</td>
                    `;
                    tableBody.appendChild(row);
                });
                offset += data.data.length;
            }
        } catch (error) {
            console.error('Error loading more transactions:', error);
        } finally {
            loading = false;
        }
    }

    // Load more data when scrolling on mobile
    if (get_device_type() === 'mobile') {
        window.addEventListener('scroll', function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                loadMoreTransactions();
            }
        });
    }
</script>
