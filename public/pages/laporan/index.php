<?php
require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/helpers.php';

// Check permissions
if (!has_permission('view_reports')) {
    echo '<div class="alert alert-danger">Anda tidak memiliki izin untuk mengakses halaman ini.</div>';
    exit;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Koperasi</h1>
    <button type="button" class="btn btn-primary" onclick="openReportModal()">
        <i class="bi bi-graph-up me-1"></i>Generate Laporan
    </button>
</div>

<div id="reportContainer">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <i class="bi bi-graph-up fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada laporan</h5>
            <p class="text-muted">Klik tombol "Generate Laporan" untuk membuat laporan baru</p>
        </div>
    </div>
</div>

<!-- Report Generation Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_from" class="form-label">Tanggal Dari</label>
                                <input type="text" class="form-control date-picker" id="date_from" name="date_from" placeholder="dd-mm-yyyy" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_to" class="form-label">Tanggal Sampai</label>
                                <input type="text" class="form-control date-picker" id="date_to" name="date_to" placeholder="dd-mm-yyyy" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="report_type" class="form-label">Jenis Laporan</label>
                        <select class="form-control" id="report_type" name="report_type" required>
                            <option value="simpanan">Laporan Simpanan</option>
                            <option value="pinjaman">Laporan Pinjaman</option>
                            <option value="anggota">Laporan Anggota</option>
                            <option value="keuangan">Laporan Keuangan</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="generateReport()">Generate</button>
            </div>
        </div>
    </div>
</div>

<script>
waitForJqueryAndRun(function() {
    // Set default dates to current month
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    $('#date_from').val(firstDay.toISOString().split('T')[0]);
    $('#date_to').val(lastDay.toISOString().split('T')[0]);

    window.openReportModal = function() {
        $('#reportModal').modal('show');
    };

    window.generateReport = function() {
        const formData = $('#reportForm').serialize();
        
        $.post('/ksp_mono/public/api/laporan.php?action=generate', formData)
            .done(function(response) {
                if (response.success) {
                    displayReport(response.data, $('#report_type').val());
                    $('#reportModal').modal('hide');
                } else {
                    alert('Error: ' + response.message);
                }
            })
            .fail(function() {
                alert('Terjadi kesalahan saat generate laporan');
            });
    };

    function displayReport(data, type) {
        let html = '<div class="card shadow-sm"><div class="card-body">';
        html += '<h5 class="card-title">Laporan ' + type.charAt(0).toUpperCase() + type.slice(1) + '</h5>';
        html += '<div class="table-responsive"><table class="table table-striped">';
        
        if (type === 'simpanan') {
            html += '<thead><tr><th>No</th><th>Tanggal</th><th>Anggota</th><th>Jumlah</th><th>Jenis</th></tr></thead><tbody>';
            data.forEach((item, index) => {
                html += `<tr><td>${index + 1}</td><td>${item.dibuat_pada}</td><td>${item.nama}</td><td>Rp ${Number(item.jumlah).toLocaleString()}</td><td>${item.jenis}</td></tr>`;
            });
        } else if (type === 'pinjaman') {
            html += '<thead><tr><th>No</th><th>Tanggal</th><th>User</th><th>Jumlah</th><th>Bunga</th><th>Status</th></tr></thead><tbody>';
            data.forEach((item, index) => {
                html += `<tr><td>${index + 1}</td><td>${item.dibuat_pada}</td><td>${item.username}</td><td>Rp ${Number(item.jumlah).toLocaleString()}</td><td>${item.bunga}%</td><td>${item.status}</td></tr>`;
            });
        } else if (type === 'anggota') {
            html += '<thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>Alamat</th><th>No HP</th><th>Status</th></tr></thead><tbody>';
            data.forEach((item, index) => {
                html += `<tr><td>${index + 1}</td><td>${item.nik}</td><td>${item.nama}</td><td>${item.alamat}</td><td>${item.no_hp}</td><td>${item.status_keanggotaan}</td></tr>`;
            });
        } else if (type === 'keuangan') {
            html += '<thead><tr><th>Jenis</th><th>Total</th><th>Jumlah Record</th></tr></thead><tbody>';
            html += `<tr><td>Total Simpanan</td><td>Rp ${Number(data.total_simpanan || 0).toLocaleString()}</td><td>${data.count_simpanan || 0}</td></tr>`;
            html += `<tr><td>Total Pinjaman</td><td>Rp ${Number(data.total_pinjaman || 0).toLocaleString()}</td><td>${data.count_pinjaman || 0}</td></tr>`;
        }
        
        html += '</tbody></table></div></div></div>';
        $('#reportContainer').html(html);
    }
});
</script>
