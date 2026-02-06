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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">Tambah Anggota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahAnggota">
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="tel" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function initAnggota() {
        if (!window.jQuery) {
            console.error('jQuery belum tersedia untuk modul anggota');
            return;
        }

        // Fungsi untuk memuat data anggota
        function loadAnggota() {
            $.ajax({
                url: '/ksp_mono/api/anggota.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let html = '';
                    if (response.status === 'success') {
                        response.data.forEach((item, index) => {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.nik}</td>
                                    <td>${item.nama}</td>
                                    <td>${item.alamat}</td>
                                    <td>${item.no_hp}</td>
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
                        html = '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
                    }
                    $('#tabelAnggota tbody').html(html);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data');
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
                    url: '/ksp_mono/api/anggota.php',
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
