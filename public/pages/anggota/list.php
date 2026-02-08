<?php
require_once __DIR__ . '/../../../app/bootstrap.php';
$auth = new Auth();
if (!$auth->check()) {
    header('Location: /ksp_mono/login.php');
    exit;
}

// Load allowed occupations from settings
$db = Database::conn();
$tenant = $db->query('SELECT allowed_occupations FROM koperasi_tenant WHERE id = 1')->fetch();
$allowedOccupations = json_decode($tenant['allowed_occupations'] ?? '[]', true);

// Load pekerjaan list
$pekerjaanList = [];
if (!empty($allowedOccupations)) {
    $in = str_repeat('?,', count($allowedOccupations) - 1) . '?';
    $stmt = $db->prepare('SELECT id, nama FROM pekerjaan_master WHERE id IN (' . $in . ')');
    $stmt->execute($allowedOccupations);
    $pekerjaanList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Anggota</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal">
            <i class="fas fa-plus"></i> Tambah Anggota
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped" id="tabelAnggota">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">Tambah Anggota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahAnggota">
                    <input type="hidden" id="anggotaId" name="id">
                    
                    <!-- Data Pribadi -->
                    <h6 class="fw-bold mb-3">Data Pribadi</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div id="emailCheck" class="form-text"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control date-picker" data-input value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="08xxxxxxxxxx">
                    </div>
                    
                    <!-- Pangkat (dynamic) -->
                    <div class="mb-3" id="pangkatContainer" style="display: none;">
                        <label for="pangkat" class="form-label">Pangkat <span class="text-danger">*</span></label>
                        <select class="form-control" id="pangkat" name="pangkat_id">
                            <option value="">Pilih Pangkat</option>
                        </select>
                    </div>
                    
                    <!-- NRP/NIP or NIK -->
                    <div class="mb-3" id="nrpNipContainer" style="display: none;">
                        <label for="nrp_nip" class="form-label">NRP/NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nrp_nip" name="nrp_nip" maxlength="18" placeholder="NRP (8 digit) atau NIP (18 digit)">
                        <div id="nrpNipCheck" class="form-text"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nik" name="nik" maxlength="16" placeholder="16 digit NIK">
                        <div id="nikCheck" class="form-text"></div>
                    </div>
                    
                    <!-- Data Keanggotaan -->
                    <h6 class="fw-bold mb-3">Data Keanggotaan</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_anggota" class="form-label">Nomor Anggota <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor_anggota" name="nomor_anggota" required>
                                <div id="nomorAnggotaCheck" class="form-text"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_keanggotaan" class="form-label">Status Keanggotaan</label>
                                <select class="form-control" id="status_keanggotaan" name="status_keanggotaan">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                    <option value="suspended">Ditangguhkan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="hapusAnggotaModal" tabindex="-1" aria-labelledby="hapusAnggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusAnggotaModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus anggota ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnKonfirmasiHapus">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
var allowedOccupations = <?php echo json_encode($allowedOccupations); ?>;
</script>

<script>
(function() {
    function initAnggota() {
        if (!window.jQuery) {
            console.error('jQuery belum tersedia untuk modul anggota');
            return;
        }

        // Helper format tanggal dd-mm-yyyy
        function formatDateId(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            return `${dd}-${mm}-${yyyy}`;
        }

        // Fungsi untuk memuat data anggota
        function loadAnggota() {
            $.ajax({
                url: '/ksp_mono/public/api/anggota.php?action=list',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let html = '';
                    if (response.status === 'success') {
                        response.data.forEach((item, index) => {
                            const joinedAt = formatDateId(item.joined_at);
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.nik}</td>
                                    <td>${item.nama}</td>
                                    <td>${item.alamat}</td>
                                    <td>${item.no_hp}</td>
                                    <td>${joinedAt}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-edit" data-id="${item.id}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-hapus" data-id="${item.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>';
                    }
                    $('#tabelAnggota tbody').html(html);
                },
                error: function() {
                    showToast('error', 'Error', 'Gagal memuat data anggota');
                }
            });
        }

        // Load data saat halaman dimuat
        $(document).ready(function() {
            loadAnggota();
            
            // Handle form submit
            $('#formTambahAnggota').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '/ksp_mono/public/api/anggota.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#tambahAnggotaModal').modal('hide');
                            $('#formTambahAnggota')[0].reset();
                            loadAnggota();
                            showToast('success', 'Berhasil', 'Data anggota berhasil ditambahkan');
                        } else {
                            showToast('error', 'Gagal', response.message || 'Terjadi kesalahan');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        showToast('error', 'Error', 'Terjadi kesalahan saat menyimpan data');
                    }
                });
            });
        });
    }

    // Jalankan setelah DOM siap; jika jQuery belum ada, retry otomatis beberapa kali
    const MAX_RETRY = 10;
    let retryCount = 0;

    function waitForJqueryThenInit() {
        if (window.jQuery) {
            initAnggota();
        } else if (retryCount < MAX_RETRY) {
            retryCount++;
            setTimeout(waitForJqueryThenInit, 150);
        } else {
            console.error('jQuery tidak tersedia setelah menunggu, modul anggota tidak dapat diinisialisasi');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', waitForJqueryThenInit);
    } else {
        waitForJqueryThenInit();
    }
})();
</script>
