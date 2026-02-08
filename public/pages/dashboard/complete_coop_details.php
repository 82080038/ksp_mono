<?php
// Dashboard component for completing cooperative details
require_once __DIR__ . '/../../../app/bootstrap.php';

// Check permissions
if (!has_permission('manage_cooperative')) {
    header('Location: /dashboard.php');
    exit;
}

// Get cooperative data
$coop_id = $_SESSION['cooperative_id'];
$stmt = $db->prepare('SELECT * FROM koperasi_tenant WHERE id = ?');
$stmt->execute([$coop_id]);
$coop = $stmt->fetch();

?>

<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-building"></i> Lengkapi Detail Koperasi</h5>
    </div>
    <div class="card-body">
        <form id="completeCoopForm">
            <!-- Badan Hukum Section -->
            <div class="mb-4">
                <h6 class="text-primary mb-3"><i class="bi bi-file-text"></i> Status Badan Hukum</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="statusBadanHukum" class="form-label"><i class="bi bi-file-earmark-check"></i> Status Badan Hukum</label>
                        <select class="form-select" name="status_badan_hukum" id="statusBadanHukum" required>
                            <option value="belum_terdaftar" <?= $coop['status_badan_hukum'] === 'belum_terdaftar' ? 'selected' : '' ?>>Belum Terdaftar</option>
                            <option value="terdaftar" <?= $coop['status_badan_hukum'] === 'terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
                            <option value="badan_hukum" <?= $coop['status_badan_hukum'] === 'badan_hukum' ? 'selected' : '' ?>>Badan Hukum</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="nomorBadanHukumContainer" style="display:<?= in_array($coop['status_badan_hukum'], ['terdaftar', 'badan_hukum']) ? 'block' : 'none' ?>">
                        <label for="nomorBadanHukum" class="form-label"><i class="bi bi-hash"></i> Nomor Badan Hukum</label>
                        <input type="text" class="form-control" name="nomor_badan_hukum" id="nomorBadanHukum" 
                            pattern="\d{12}" title="Nomor Badan Hukum harus 12 digit angka"
                            value="<?= htmlspecialchars($coop['nomor_badan_hukum'] ?? '') ?>">
                        <small class="text-muted">12 digit angka (diisi jika status Terdaftar/Badan Hukum)</small>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="mb-4">
                <h6 class="text-primary mb-3"><i class="bi bi-telephone"></i> Kontak Resmi</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kontakField" class="form-label"><i class="bi bi-phone"></i> Kontak</label>
                        <input type="text" class="form-control phone-field" name="kontak" id="kontakField" 
                            required value="<?= htmlspecialchars($coop['kontak'] ?? '') ?>">
                        <small class="text-muted">Format: 08XX-XXXX-XXXX</small>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Simpan Detail
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Same conditional field logic as registration
$('#statusBadanHukum').on('change', function() {
    $('#nomorBadanHukumContainer').toggle($(this).val() !== 'belum_terdaftar');
});

// Cooperative form validation
$('#completeCoopForm').on('submit', function(e) {
    const form = this;
    
    // Validate Badan Hukum number if required
    const status = $('#statusBadanHukum').val();
    const nomorBH = $('#nomorBadanHukum').val();
    
    if ((status === 'terdaftar' || status === 'badan_hukum') && 
        (!/^\d{12}$/.test(nomorBH))) {
        e.preventDefault();
        $('#nomorBadanHukum').addClass('is-invalid');
        return;
    }
    
    if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    $(form).addClass('was-validated');
});

// Initialize phone field masking
$('.phone-field').on('input', function() {
    $(this).val(formatPhoneNumber($(this).val()));
});
</script>
