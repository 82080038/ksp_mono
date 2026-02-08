<?php
// Simpanan transactions list
$db = Database::conn();
$stmt = $db->query('SELECT s.*, o.nama_lengkap AS nama
                    FROM simpanan_transaksi s
                    JOIN anggota a ON a.id = s.anggota_id
                    LEFT JOIN orang o ON o.pengguna_id = a.user_id
                    ORDER BY s.id DESC');
$transactions = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Transaksi Simpanan</h1>
    <button type="button" class="btn btn-primary" onclick="openSimpananModal()">
        <i class="bi bi-plus-circle me-1"></i>Tambah Transaksi
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Jenis</th>
                        <th>Anggota</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada transaksi</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($transactions as $index => $trans): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo format_date($trans['dibuat_pada']); ?></td>
                        <td><?php echo format_money($trans['jumlah']); ?></td>
                        <td><?php echo ucfirst($trans['jenis']); ?></td>
                        <td><?php echo htmlspecialchars($trans['nama'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($trans['keterangan'] ?? '-'); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editSimpanan(<?php echo $trans['id']; ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSimpanan(<?php echo $trans['id']; ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Simpanan Modal -->
<div class="modal fade" id="simpananModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="simpananModalLabel">Tambah Transaksi Simpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="simpananForm">
                    <input type="hidden" id="simpananId" name="id">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="text" class="form-control date-picker" data-input id="tanggal" name="tanggal" value="<?php echo format_date_input(date('Y-m-d')); ?>" placeholder="dd-mm-yyyy" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                        <input type="text" class="form-control money-input" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis</label>
                        <select class="form-control" id="jenis" name="jenis" required>
                            <option value="setoran">Setoran</option>
                            <option value="penarikan">Penarikan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="anggota_id" class="form-label">Anggota</label>
                        <select class="form-control" id="anggota_id" name="anggota_id" required>
                            <option value="">Pilih Anggota</option>
                            <?php
                            $anggota = $db->query('SELECT a.id, o.nama_lengkap FROM anggota a JOIN orang o ON a.user_id = o.pengguna_id ORDER BY o.nama_lengkap')->fetchAll();
                            foreach ($anggota as $ang) {
                                echo '<option value="' . $ang['id'] . '">' . htmlspecialchars($ang['nama_lengkap']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveSimpanan()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSimpananModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus transaksi ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteSimpanan()">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteSimpananId = null;

waitForJqueryAndRun(function() {
    function reloadTable() {
        location.reload();
    }

    window.openSimpananModal = function(id = null) {
        $('#simpananModalLabel').text(id ? 'Edit Transaksi Simpanan' : 'Tambah Transaksi Simpanan');
        $('#simpananId').val('');
        $('#simpananForm')[0].reset();
        $('#tanggal').val(new Date().toISOString().split('T')[0]);
        
        if (id) {
            $.get('/ksp_mono/public/api/simpanan.php?action=get&id=' + id)
                .done(function(data) {
                    if (data.success) {
                        const trans = data.data;
                        $('#simpananId').val(trans.id);
                        $('#tanggal').val(trans.dibuat_pada.split(' ')[0]);
                        $('#jumlah').val(trans.jumlah);
                        $('#jenis').val(trans.jenis);
                        $('#anggota_id').val(trans.anggota_id);
                        $('#keterangan').val(trans.keterangan);
                    }
                });
        }
        
        const modal = new bootstrap.Modal(document.getElementById('simpananModal'));
        modal.show();
    };

    window.editSimpanan = function(id) {
        openSimpananModal(id);
    };

    window.deleteSimpanan = function(id) {
        deleteSimpananId = id;
        const delModal = new bootstrap.Modal(document.getElementById('deleteSimpananModal'));
        delModal.show();
    };

    window.saveSimpanan = function() {
        const formData = $('#simpananForm').serialize();
        const isEdit = $('#simpananId').val() !== '';
        
        $.post('/ksp_mono/public/api/simpanan.php?action=' + (isEdit ? 'update' : 'create'), formData)
            .done(function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('simpananModal'));
                    modal.hide();
                    reloadTable();
                } else {
                    alert('Error: ' + response.message);
                }
            })
            .fail(function() {
                alert('Terjadi kesalahan');
            });
    };

    window.confirmDeleteSimpanan = function() {
        if (deleteSimpananId) {
            $.post('/ksp_mono/public/api/simpanan.php?action=delete', { id: deleteSimpananId })
                .done(function(response) {
                    if (response.success) {
                        const delModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteSimpananModal'));
                        delModal.hide();
                        reloadTable();
                    } else {
                        alert('Error: ' + response.message);
                    }
                });
        }
    };
});
</script>
