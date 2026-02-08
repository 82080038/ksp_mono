<?php
require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/helpers.php';

// Check permissions
if (!has_permission('admin_access')) {
    echo '<div class="alert alert-danger">Anda tidak memiliki izin untuk mengakses halaman ini.</div>';
    exit;
}

// Get current cooperative data
$cooperative = $_SESSION['cooperatives'][0] ?? [];

// Get occupations
$db = Database::conn();
$occupations = $db->query('SELECT * FROM pekerjaan_master ORDER BY nama_pekerjaan')->fetchAll();

// Get current allowed occupations
$allowedOccupations = json_decode($cooperative['allowed_occupations'] ?? '[]', true) ?: [];

// Get settings
$savingsSettings = json_decode($cooperative['savings_settings'] ?? '{}', true);
$loansSettings = json_decode($cooperative['loans_settings'] ?? '{}', true);
$reportsSettings = json_decode($cooperative['reports_settings'] ?? '{}', true);
$memberSettings = json_decode($cooperative['member_settings'] ?? '{}', true);
$complianceSettings = json_decode($cooperative['compliance_settings'] ?? '{}', true);
$jenisKoperasiDisplay = $cooperative['jenis_koperasi'] ?? '';

// Loan types master
$loanTypes = $db->query("SELECT * FROM loan_types WHERE is_active = 1 ORDER BY id")->fetchAll();

// Savings types master
$savingsTypes = $db->query("SELECT * FROM savings_types WHERE is_active = 1 ORDER BY id")->fetchAll();

// Pengurus & Pengawas
$pengurusStmt = $db->prepare('SELECT kp.id, kp.jabatan, kp.orang_id, kp.tanggal_mulai, kp.tanggal_akhir, o.nama_lengkap FROM koperasi_pengurus kp JOIN orang o ON kp.orang_id = o.id WHERE kp.koperasi_tenant_id = ? ORDER BY kp.jabatan, kp.id DESC');
$pengurusStmt->execute([$cooperative['id']]);
$pengurusData = $pengurusStmt->fetchAll();

// Integration settings (single row)
$integrationSettings = $db->query("SELECT * FROM integration_settings WHERE id = 1")->fetch() ?: [];

// RAT checklist
$ratChecklistStmt = $db->prepare('SELECT * FROM rat_checklist WHERE koperasi_tenant_id = ? ORDER BY order_no, id');
$ratChecklistStmt->execute([$cooperative['id']]);
$ratChecklist = $ratChecklistStmt->fetchAll();

// Get orang list for selects
$orangList = $db->query('SELECT id, nama_lengkap FROM orang ORDER BY nama_lengkap')->fetchAll();

// Render ketua and sekretaris names from pengurus table
$ketuaNama = '';
$sekretarisNama = '';
$ketuaOrangId = 0;
$sekretarisOrangId = 0;

$ketuaStmt = $db->prepare('SELECT kp.orang_id, o.nama_lengkap FROM koperasi_pengurus kp JOIN orang o ON kp.orang_id = o.id WHERE kp.koperasi_tenant_id = ? AND kp.jabatan = ? AND kp.tanggal_akhir IS NULL');
$ketuaStmt->execute([$cooperative['id'], 'ketua']);
$ketuaData = $ketuaStmt->fetch();
if ($ketuaData) {
    $ketuaNama = $ketuaData['nama_lengkap'];
    $ketuaOrangId = $ketuaData['orang_id'];
}

$sekretarisStmt = $db->prepare('SELECT kp.orang_id, o.nama_lengkap FROM koperasi_pengurus kp JOIN orang o ON kp.orang_id = o.id WHERE kp.koperasi_tenant_id = ? AND kp.jabatan = ? AND kp.tanggal_akhir IS NULL');
$sekretarisStmt->execute([$cooperative['id'], 'sekretaris']);
$sekretarisData = $sekretarisStmt->fetch();
if ($sekretarisData) {
    $sekretarisNama = $sekretarisData['nama_lengkap'];
    $sekretarisOrangId = $sekretarisData['orang_id'];
}
?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Pengaturan Sistem</h5>
    </div>

<!-- Integration Modal -->
<div class="modal fade" id="integrationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Integrasi & Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="integrationForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Reminder Jatuh Tempo (hari sebelum)</label>
                            <input type="text" name="reminder_due_days" id="reminder_due_days" class="form-control numeric-input" value="<?php echo htmlspecialchars($integrationSettings['reminder_due_days'] ?? 3); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Channel Reminder</label>
                            <select name="reminder_channel" id="reminder_channel" class="form-control">
                                <?php $rc = $integrationSettings['reminder_channel'] ?? 'email'; ?>
                                <option value="email" <?php echo $rc==='email'?'selected':''; ?>>Email</option>
                                <option value="wa" <?php echo $rc==='wa'?'selected':''; ?>>WhatsApp</option>
                                <option value="sms" <?php echo $rc==='sms'?'selected':''; ?>>SMS</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Channel Pembayaran</label>
                            <select name="payment_channel" id="payment_channel" class="form-control">
                                <?php $pc = $integrationSettings['payment_channel'] ?? 'transfer'; ?>
                                <option value="transfer" <?php echo $pc==='transfer'?'selected':''; ?>>Transfer</option>
                                <option value="va" <?php echo $pc==='va'?'selected':''; ?>>Virtual Account</option>
                                <option value="cash" <?php echo $pc==='cash'?'selected':''; ?>>Cash</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Biaya Transfer</label>
                            <input type="text" name="transfer_fee" id="transfer_fee" class="form-control money-input" value="<?php echo htmlspecialchars($integrationSettings['transfer_fee'] ?? 0); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cut-off Waktu Pembayaran</label>
                            <input type="text" name="cutoff_time" id="cutoff_time" class="form-control" value="<?php echo htmlspecialchars($integrationSettings['cutoff_time'] ?? '17:00'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reminder RAT (hari sebelum)</label>
                            <input type="text" name="rat_reminder_days" id="rat_reminder_days" class="form-control numeric-input" value="<?php echo htmlspecialchars($integrationSettings['rat_reminder_days'] ?? 7); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveIntegration">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- RAT Modal -->
<div class="modal fade" id="ratModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checklist RAT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ratForm">
                    <input type="hidden" name="id" id="rat_id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Item</label>
                            <input type="text" name="item" id="rat_item" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Urutan</label>
                            <input type="text" name="order_no" id="rat_order" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="rat_status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="rat_required" name="required" checked>
                                <label class="form-check-label" for="rat_required">Wajib</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" id="rat_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="btnDeleteRat" style="display:none;">Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveRat">Simpan</button>
            </div>
        </div>
    </div>
</div>
    <div class="card-body">
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">Umum</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="occupations-tab" data-bs-toggle="tab" data-bs-target="#occupations" type="button" role="tab">Pekerjaan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pengurus-tab" data-bs-toggle="tab" data-bs-target="#pengurus" type="button" role="tab">Pengurus</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="savings-tab" data-bs-toggle="tab" data-bs-target="#savings" type="button" role="tab">Simpanan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="loans-tab" data-bs-toggle="tab" data-bs-target="#loans" type="button" role="tab">Pinjaman</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="member-tab" data-bs-toggle="tab" data-bs-target="#member" type="button" role="tab">Anggota</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="compliance-tab" data-bs-toggle="tab" data-bs-target="#compliance" type="button" role="tab">Compliance</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">Laporan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="integration-tab" data-bs-toggle="tab" data-bs-target="#integration" type="button" role="tab">Integrasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rat-tab" data-bs-toggle="tab" data-bs-target="#rat" type="button" role="tab">RAT</button>
            </li>
        </ul>
        
        <!-- Tab content -->
        <form method="post" action="?modul=pengaturan&action=save" enctype="multipart/form-data">
            <div class="tab-content mt-3" id="settingsTabContent">
                <?php include __DIR__ . '/partials/tab_occupations.php'; ?>
                <?php include __DIR__ . '/partials/tab_pengurus.php'; ?>
                <?php include __DIR__ . '/partials/tab_general.php'; ?>
                <?php include __DIR__ . '/partials/tab_savings.php'; ?>
                <?php include __DIR__ . '/partials/tab_loans.php'; ?>
                <?php include __DIR__ . '/partials/tab_member.php'; ?>
                <?php include __DIR__ . '/partials/tab_compliance.php'; ?>
                <?php include __DIR__ . '/partials/tab_reports.php'; ?>
                <?php include __DIR__ . '/partials/tab_integration.php'; ?>
                <?php include __DIR__ . '/partials/tab_rat.php'; ?>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Loan Types Modal -->
<div class="modal fade" id="loanTypesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelola Jenis Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="loanTypeForm">
                    <input type="hidden" name="id" id="loan_type_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <select name="name" id="loan_type_name" class="form-control" required>
                                <option value="">-- Pilih jenis --</option>
                                <option value="Konsumtif">Konsumtif</option>
                                <option value="Produktif">Produktif</option>
                                <option value="Darurat">Darurat</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bunga (%)</label>
                            <input type="text" name="interest_rate" id="loan_type_interest_rate" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Metode</label>
                            <select name="interest_method" id="loan_type_interest_method" class="form-control">
                                <option value="flat">Flat</option>
                                <option value="menurun">Menurun (anuitas)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tenor Maks (bulan)</label>
                            <input type="text" name="max_tenor_months" id="loan_type_max_tenor" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rasio Plafon/Simpanan (x)</label>
                            <input type="text" name="max_plafon_savings_ratio" id="loan_type_plafon_ratio" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rasio Angsuran/Penghasilan (%)</label>
                            <input type="text" name="max_installment_income_ratio" id="loan_type_installment_ratio" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Biaya Admin (Rp)</label>
                            <input type="text" name="admin_fee" id="loan_type_admin_fee" class="form-control money-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provisi (%)</label>
                            <input type="text" name="provision_fee" id="loan_type_provision_fee" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Denda (%/hari)</label>
                            <input type="text" name="penalty_rate" id="loan_type_penalty_rate" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Asuransi (% premi)</label>
                            <input type="text" name="insurance_rate" id="loan_type_insurance_rate" class="form-control numeric-input" value="0">
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" value="1" id="loan_type_require_insurance" name="require_insurance">
                                <label class="form-check-label" for="loan_type_require_insurance">Wajib asuransi</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">LTV / Nilai Agunan (%)</label>
                            <input type="text" name="ltv_ratio" id="loan_type_ltv_ratio" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Agunan</label>
                            <input type="text" name="collateral_type" id="loan_type_collateral_type" class="form-control" value="">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="description" id="loan_type_description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="loan_type_active" name="is_active" checked>
                                <label class="form-check-label" for="loan_type_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="btnDeleteLoanType" style="display:none;">Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveLoanType">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Savings Types Modal -->
<div class="modal fade" id="savingsTypesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelola Jenis Simpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="savingsTypeForm">
                    <input type="hidden" name="id" id="savings_type_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="savings_type_name" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bunga (%)</label>
                            <input type="text" name="interest_rate" id="savings_type_interest_rate" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Min Setoran (Rp)</label>
                            <input type="text" name="min_deposit" id="savings_type_min_deposit" class="form-control money-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Biaya Admin (Rp)</label>
                            <input type="text" name="admin_fee" id="savings_type_admin_fee" class="form-control money-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Denda (%/hari)</label>
                            <input type="text" name="penalty_rate" id="savings_type_penalty_rate" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lock Period (hari)</label>
                            <input type="text" name="lock_period_days" id="savings_type_lock_period" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Biaya Tarik Awal (%)</label>
                            <input type="text" name="early_withdraw_fee" id="savings_type_early_withdraw_fee" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Saldo Mengendap Min (Rp)</label>
                            <input type="text" name="min_balance" id="savings_type_min_balance" class="form-control money-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Keterangan</label>
                            <textarea name="description" id="savings_type_description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="savings_type_active" name="is_active" checked>
                                <label class="form-check-label" for="savings_type_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="btnDeleteSavingsType" style="display:none;">Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveSavingsType">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Pengurus Modal -->
<div class="modal fade" id="pengurusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelola Pengurus / Pengawas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="pengurusForm">
                    <input type="hidden" name="id" id="pengurus_id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" id="pengurus_jabatan" class="form-control" required>
                                <option value="">-- Pilih jabatan --</option>
                                <option value="ketua">Ketua</option>
                                <option value="sekretaris">Sekretaris</option>
                                <option value="bendahara">Bendahara</option>
                                <option value="pengawas">Pengawas</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Orang</label>
                            <select name="orang_id" id="pengurus_orang_id" class="form-control" required>
                                <option value="">-- Pilih orang --</option>
                                <?php foreach ($orangList as $o): ?>
                                    <option value="<?php echo $o['id']; ?>"><?php echo htmlspecialchars($o['nama_lengkap']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="pengurus_tanggal_mulai" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="pengurus_tanggal_akhir" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="btnDeletePengurus" style="display:none;">Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSavePengurus">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Pekerjaan Settings Modal -->
<div class="modal fade" id="pekerjaanSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan Pekerjaan Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Centang pekerjaan yang ingin diizinkan untuk anggota baru.</p>
                <form id="pekerjaanSettingsForm">
                    <div class="row">
                        <?php foreach ($occupations as $occ): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_occupations[]" value="<?php echo $occ['id']; ?>" id="occ_<?php echo $occ['id']; ?>" <?php echo in_array($occ['id'], $allowedOccupations) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="occ_<?php echo $occ['id']; ?>">
                                        <?php echo htmlspecialchars($occ['nama_pekerjaan']); ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="savePekerjaanSettings()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Integration Modal -->
<div class="modal fade" id="integrationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Integrasi & Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="integrationForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Reminder Jatuh Tempo (hari sebelum)</label>
                            <input type="text" name="reminder_due_days" id="reminder_due_days" class="form-control numeric-input" value="<?php echo htmlspecialchars($integrationSettings['reminder_due_days'] ?? 3); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Channel Reminder</label>
                            <select name="reminder_channel" id="reminder_channel" class="form-control">
                                <?php $rc = $integrationSettings['reminder_channel'] ?? 'email'; ?>
                                <option value="email" <?php echo $rc==='email'?'selected':''; ?>>Email</option>
                                <option value="wa" <?php echo $rc==='wa'?'selected':''; ?>>WhatsApp</option>
                                <option value="sms" <?php echo $rc==='sms'?'selected':''; ?>>SMS</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Channel Pembayaran</label>
                            <select name="payment_channel" id="payment_channel" class="form-control">
                                <?php $pc = $integrationSettings['payment_channel'] ?? 'transfer'; ?>
                                <option value="transfer" <?php echo $pc==='transfer'?'selected':''; ?>>Transfer</option>
                                <option value="va" <?php echo $pc==='va'?'selected':''; ?>>Virtual Account</option>
                                <option value="cash" <?php echo $pc==='cash'?'selected':''; ?>>Cash</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Biaya Transfer</label>
                            <input type="text" name="transfer_fee" id="transfer_fee" class="form-control money-input" value="<?php echo htmlspecialchars($integrationSettings['transfer_fee'] ?? 0); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cut-off Waktu Pembayaran</label>
                            <input type="text" name="cutoff_time" id="cutoff_time" class="form-control" value="<?php echo htmlspecialchars($integrationSettings['cutoff_time'] ?? '17:00'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reminder RAT (hari sebelum)</label>
                            <input type="text" name="rat_reminder_days" id="rat_reminder_days" class="form-control numeric-input" value="<?php echo htmlspecialchars($integrationSettings['rat_reminder_days'] ?? 7); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveIntegration">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- RAT Modal -->
<div class="modal fade" id="ratModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checklist RAT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ratForm">
                    <input type="hidden" name="id" id="rat_id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Item</label>
                            <input type="text" name="item" id="rat_item" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Urutan</label>
                            <input type="text" name="order_no" id="rat_order" class="form-control numeric-input" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="rat_status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="rat_required" name="required" checked>
                                <label class="form-check-label" for="rat_required">Wajib</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" id="rat_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="btnDeleteRat" style="display:none;">Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveRat">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- General Docs Modal (placeholder) -->
<div class="modal fade" id="generalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload/Edit Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-0">Form upload dokumen belum diimplementasi penuh. Untuk sekarang, unggah dokumen via halaman terpisah atau hubungi admin.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
waitForJqueryAndRun(function() {
    // Loan types CRUD
    const loanTypesData = <?php echo json_encode($loanTypes); ?>;

    function resetLoanTypeForm() {
        $('#loan_type_id').val('');
        $('#loan_type_name').val('');
        $('#loan_type_interest_rate').val('0');
        $('#loan_type_interest_method').val('flat');
        $('#loan_type_max_tenor').val('0');
        $('#loan_type_plafon_ratio').val('0');
        $('#loan_type_installment_ratio').val('0');
        $('#loan_type_admin_fee').val('0');
        $('#loan_type_provision_fee').val('0');
        $('#loan_type_penalty_rate').val('0');
        $('#loan_type_insurance_rate').val('0');
        $('#loan_type_require_insurance').prop('checked', false);
        $('#loan_type_ltv_ratio').val('0');
        $('#loan_type_collateral_type').val('');
        $('#loan_type_description').val('');
        $('#loan_type_active').prop('checked', true);
        $('#btnDeleteLoanType').hide();
    }

    window.openLoanTypeModal = function(id) {
        resetLoanTypeForm();
        if (id) {
            const lt = loanTypesData.find(item => item.id == id);
            if (lt) {
                $('#loan_type_id').val(lt.id);
                $('#loan_type_name').val(lt.name);
                $('#loan_type_interest_rate').val(lt.interest_rate);
                $('#loan_type_interest_method').val(lt.interest_method);
                $('#loan_type_max_tenor').val(lt.max_tenor_months);
                $('#loan_type_plafon_ratio').val(lt.max_plafon_savings_ratio);
                $('#loan_type_installment_ratio').val(lt.max_installment_income_ratio);
                $('#loan_type_admin_fee').val(lt.admin_fee);
                $('#loan_type_provision_fee').val(lt.provision_fee);
                $('#loan_type_penalty_rate').val(lt.penalty_rate);
                $('#loan_type_insurance_rate').val(lt.insurance_rate || 0);
                $('#loan_type_require_insurance').prop('checked', lt.require_insurance == 1);
                $('#loan_type_ltv_ratio').val(lt.ltv_ratio || 0);
                $('#loan_type_collateral_type').val(lt.collateral_type || '');
                $('#loan_type_description').val(lt.description);
                $('#loan_type_active').prop('checked', lt.is_active == 1);
                $('#btnDeleteLoanType').show().data('id', lt.id);
            }
        }
        $('#loanTypesModal').modal('show');
    }

    $('#btnSaveLoanType').on('click', function() {
        const formData = $('#loanTypeForm').serialize();
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=save_loan_type', formData)
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menyimpan');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menyimpan'); });
    });

    $('#btnDeleteLoanType').on('click', function() {
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Hapus jenis pinjaman ini?')) return;
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=delete_loan_type', { id })
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menghapus');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menghapus'); });
    });

    // Klik baris tabel untuk edit
    $('.loan-type-row').on('click', function(e) {
        // hindari trigger jika klik tombol aksi
        if ($(e.target).closest('button').length) return;
        const id = $(this).data('id');
        openLoanTypeModal(id);
    });

    // ===== Modal open functions =====
    window.openLoanTypesModal = function() {
        $('#loanTypesModal').modal('show');
    };
    window.openSavingsTypesModal = function() {
        $('#savingsTypesModal').modal('show');
    };
    window.openPengurusModal = function() {
        $('#pengurusModal').modal('show');
    };
    window.openPekerjaanModal = function() {
        $('#pekerjaanSettingsModal').modal('show');
    };
    window.openGeneralModal = function() {
        $('#generalModal').modal('show');
    };
    window.openIntegrationModal = function() {
        $('#integrationModal').modal('show');
    };
    window.openRatModal = function(id) {
        $('#rat_id').val('');
        $('#rat_item').val('');
        $('#rat_status').val('pending');
        $('#rat_required').prop('checked', true);
        $('#rat_notes').val('');
        $('#rat_order').val('0');
        $('#btnDeleteRat').hide();
        if (id) {
            const row = $('.rat-row[data-id="'+id+'"]');
            if (row.length) {
                $('#rat_id').val(id);
                $('#rat_item').val(row.data('item'));
                $('#rat_status').val(row.data('status'));
                $('#rat_required').prop('checked', row.data('required') == 1);
                $('#rat_notes').val(row.data('notes'));
                $('#rat_order').val(row.data('order'));
                $('#btnDeleteRat').show().data('id', id);
            }
        }
        $('#ratModal').modal('show');
    };
    // Integration save
    $('#btnSaveIntegration').on('click', function() {
        const formData = $('#integrationForm').serialize();
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=save_integration', formData)
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menyimpan');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menyimpan'); });
    });
    // RAT save
    $('#btnSaveRat').on('click', function() {
        const formData = $('#ratForm').serialize();
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=save_rat_item', formData)
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menyimpan');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menyimpan'); });
    });
    // RAT delete
    $('#btnDeleteRat').on('click', function() {
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Hapus item checklist ini?')) return;
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=delete_rat_item', { id })
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menghapus');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menghapus'); });
    });
    // RAT table click to edit
    $('.rat-row').on('click', function(e) {
        if ($(e.target).closest('button').length) return;
        const id = $(this).data('id');
        openRatModal(id);
    });
    $('.btn-delete-rat').on('click', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Hapus item checklist ini?')) return;
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=delete_rat_item', { id })
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menghapus');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menghapus'); });
    });
    const savingsTypesData = <?php echo json_encode($savingsTypes); ?>;

    function resetSavingsTypeForm() {
        $('#savings_type_id').val('');
        $('#savings_type_name').val('');
        $('#savings_type_interest_rate').val('0');
        $('#savings_type_min_deposit').val('0');
        $('#savings_type_admin_fee').val('0');
        $('#savings_type_penalty_rate').val('0');
        $('#savings_type_lock_period').val('0');
        $('#savings_type_early_withdraw_fee').val('0');
        $('#savings_type_min_balance').val('0');
        $('#savings_type_description').val('');
        $('#savings_type_active').prop('checked', true);
        $('#btnDeleteSavingsType').hide();
    }

    window.openSavingsTypeModal = function(id) {
        resetSavingsTypeForm();
        if (id) {
            const st = savingsTypesData.find(item => item.id == id);
            if (st) {
                $('#savings_type_id').val(st.id);
                $('#savings_type_name').val(st.name);
                $('#savings_type_interest_rate').val(st.interest_rate);
                $('#savings_type_min_deposit').val(st.min_deposit);
                $('#savings_type_admin_fee').val(st.admin_fee);
                $('#savings_type_penalty_rate').val(st.penalty_rate);
                $('#savings_type_lock_period').val(st.lock_period_days || 0);
                $('#savings_type_early_withdraw_fee').val(st.early_withdraw_fee || 0);
                $('#savings_type_min_balance').val(st.min_balance || 0);
                $('#savings_type_description').val(st.description);
                $('#savings_type_active').prop('checked', st.is_active == 1);
                $('#btnDeleteSavingsType').show().data('id', st.id);
            }
        }
        $('#savingsTypesModal').modal('show');
    }

    $('#btnSaveSavingsType').on('click', function() {
        const formData = $('#savingsTypeForm').serialize();
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=save_savings_type', formData)
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menyimpan');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menyimpan'); });
    });

    $('#btnDeleteSavingsType').on('click', function() {
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Hapus jenis simpanan ini?')) return;
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=delete_savings_type', { id })
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menghapus');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menghapus'); });
    });

    // Klik baris tabel simpanan untuk edit
    $('.savings-type-row').on('click', function(e) {
        if ($(e.target).closest('button').length) return;
        const id = $(this).data('id');
        openSavingsTypeModal(id);
    });

    // ===== Pengurus & Pengawas CRUD =====
    const pengurusData = <?php echo json_encode($pengurusData); ?>;

    function resetPengurusForm() {
        $('#pengurus_id').val('');
        $('#pengurus_jabatan').val('');
        $('#pengurus_orang_id').val('');
        $('#pengurus_tanggal_mulai').val('');
        $('#pengurus_tanggal_akhir').val('');
        $('#btnDeletePengurus').hide();
    }

    window.openPengurusModal = function(id) {
        resetPengurusForm();
        if (id) {
            const pg = pengurusData.find(item => item.id == id);
            if (pg) {
                $('#pengurus_id').val(pg.id);
                $('#pengurus_jabatan').val(pg.jabatan);
                $('#pengurus_orang_id').val(pg.orang_id);
                $('#pengurus_tanggal_mulai').val(pg.tanggal_mulai);
                $('#pengurus_tanggal_akhir').val(pg.tanggal_akhir);
                $('#btnDeletePengurus').show().data('id', pg.id);
            }
        }
        $('#pengurusModal').modal('show');
    }

    $('#btnSavePengurus').on('click', function() {
        const formData = $('#pengurusForm').serialize();
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=save_pengurus', formData)
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menyimpan');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menyimpan'); });
    });

    $('#btnDeletePengurus').on('click', function() {
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Hapus pengurus/pengawas ini?')) return;
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=delete_pengurus', { id })
            .done(function(resp) {
                try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'Gagal menghapus');
                }
            })
            .fail(function() { alert('Terjadi kesalahan saat menghapus'); });
    });

    $('.pengurus-row').on('click', function(e) {
        if ($(e.target).closest('button').length) return;
        const id = $(this).data('id');
        openPengurusModal(id);
    });

    // Picker sederhana: gunakan prompt untuk pilih ID dari master (untuk cepat)
    window.openLoanTypePicker = function() {
        if (!loanTypesData || !loanTypesData.length) {
            alert('Master jenis pinjaman kosong. Tambahkan dulu.');
            return;
        }
        const list = loanTypesData.map(lt => `${lt.id}: ${lt.name}`).join('\n');
        const chosen = prompt('Masukkan ID jenis pinjaman:\n' + list, $('#loan_type_id_field').val() || '');
        if (!chosen) return;
        const lt = loanTypesData.find(item => item.id == chosen);
        if (!lt) {
            alert('ID tidak ditemukan');
            return;
        }
        $('#loan_type_id_field').val(lt.id);
        $('#loan_type_name_field').val(lt.name);
        $('#loans_interest_rate').val(lt.interest_rate);
        $('#loans_interest_method').val(lt.interest_method);
        $('#loans_max_tenor_months').val(lt.max_tenor_months);
        $('#loans_max_plafon_savings_ratio').val(lt.max_plafon_savings_ratio);
        $('#loans_max_installment_income_ratio').val(lt.max_installment_income_ratio);
        $('#loans_admin_fee').val(lt.admin_fee);
        $('#loans_provision_fee').val(lt.provision_fee);
        $('#loans_penalty_rate').val(lt.penalty_rate);
        if (!$('#loans_description').val()) {
            $('#loans_description').val(lt.description);
        }
    }

    // Auto-fill from master loan type
    $('#loan_type_select').on('change', function() {
        const opt = $(this).find('option:selected');
        const id = opt.val();
        const name = opt.data('name') || '';
        if (!id) {
            return;
        }
        $('#loan_type_id_field').val(id);
        $('#loan_type_name_field').val(name);
        $('#loans_interest_rate').val(opt.data('rate') || '0');
        $('#loans_interest_method').val(opt.data('method') || 'flat');
        $('#loans_max_tenor_months').val(opt.data('tenor') || '0');
        $('#loans_max_plafon_savings_ratio').val(opt.data('plafon') || '0');
        $('#loans_max_installment_income_ratio').val(opt.data('install') || '0');
        $('#loans_admin_fee').val(opt.data('admin') || '0');
        $('#loans_provision_fee').val(opt.data('provision') || '0');
        $('#loans_penalty_rate').val(opt.data('penalty') || '0');
        const desc = opt.data('desc') || '';
        if (!$('#loans_description').val()) {
            $('#loans_description').val(desc);
        }
    });

    window.editPekerjaanSettings = function() {
        $('#pekerjaanSettingsModal').modal('show');
    };

    window.savePekerjaanSettings = function() {
        const formData = $('#pekerjaanSettingsForm').serialize();
        
        $.post('/ksp_mono/public/pages/pengaturan/index.php?action=save_pekerjaan', formData)
            .done(function(response) {
                if (response.success) {
                    $('#pekerjaanSettingsModal').modal('hide');
                    location.reload(); // Reload to show updated settings
                } else {
                    alert('Error: ' + response.message);
                }
            })
            .fail(function() {
                alert('Terjadi kesalahan');
            });
    };
});
</script>

<script>
waitForJqueryAndRun(function() {
    function get_device_type() {
        const width = window.innerWidth;
        if (width < 768) return 'mobile';
        if (width < 1024) return 'tablet';
        return 'desktop';
    }

    function highlightActiveNav() {
        // Function to highlight active navigation - already handled in PHP
    }
});
</script>
