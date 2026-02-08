<?php
// Pinjaman list
$db = Database::conn();
$stmt = $db->query('SELECT p.*, o.nama_lengkap AS nama
                    FROM pinjaman p
                    LEFT JOIN anggota a ON a.id = p.anggota_id
                    LEFT JOIN orang o ON o.pengguna_id = a.user_id
                    ORDER BY p.id DESC');
$pinjaman = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Pinjaman</h1>
    <button type="button" class="btn btn-primary" onclick="openPinjamanModal()">
        <i class="bi bi-plus-circle me-1"></i>Tambah Pinjaman
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Jumlah</th>
                        <th>Bunga (%)</th>
                        <th>Tenor (bulan)</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pinjaman)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data pinjaman</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($pinjaman as $index => $loan): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($loan['nama'] ?? 'N/A'); ?></td>
                        <td><?php echo format_money($loan['jumlah']); ?></td>
                        <td><?php echo $loan['bunga']; ?>%</td>
                        <td><?php echo $loan['tenor']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $loan['status'] === 'active' ? 'success' : ($loan['status'] === 'paid' ? 'info' : 'secondary'); ?>">
                                <?php echo ucfirst($loan['status']); ?>
                            </span>
                        </td>
                        <td><?php echo format_date($loan['dibuat_pada']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editPinjaman(<?php echo $loan['id']; ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deletePinjaman(<?php echo $loan['id']; ?>)">
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

<!-- Pinjaman Modal -->
<div class="modal fade" id="pinjamanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pinjamanModalLabel">Tambah Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="pinjamanForm">
                    <input type="hidden" id="pinjamanId" name="id">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">Pilih User</option>
                            <?php
                            $users = $db->query('SELECT id, username FROM pengguna ORDER BY username')->fetchAll();
                            foreach ($users as $user) {
                                echo '<option value="' . $user['id'] . '">' . htmlspecialchars($user['username']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Pinjaman (Rp)</label>
                        <input type="text" class="form-control money-input" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="bunga" class="form-label">Bunga (%)</label>
                        <input type="text" class="form-control numeric-input" id="bunga" name="bunga" required>
                    </div>
                    <div class="mb-3">
                        <label for="tenor" class="form-label">Tenor (bulan)</label>
                        <input type="text" class="form-control numeric-input" id="tenor" name="tenor" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="paid">Lunas</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="savePinjaman()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deletePinjamanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pinjaman ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeletePinjaman()">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
let deletePinjamanId = null;

waitForJqueryAndRun(function() {
    function reloadTable() {
        location.reload();
    }

    window.openPinjamanModal = function(id = null) {
        $('#pinjamanModalLabel').text(id ? 'Edit Pinjaman' : 'Tambah Pinjaman');
        $('#pinjamanId').val('');
        $('#pinjamanForm')[0].reset();
        
        if (id) {
            $.get('/ksp_mono/public/api/pinjaman.php?action=get&id=' + id)
                .done(function(data) {
                    if (data.success) {
                        const loan = data.data;
                        $('#pinjamanId').val(loan.id);
                        $('#user_id').val(loan.user_id);
                        $('#jumlah').val(loan.jumlah);
                        $('#bunga').val(loan.bunga);
                        $('#tenor').val(loan.tenor);
                        $('#status').val(loan.status);
                    }
                });
        }
        
        const modal = new bootstrap.Modal(document.getElementById('pinjamanModal'));
        modal.show();
    };

    window.editPinjaman = function(id) {
        openPinjamanModal(id);
    };

    window.deletePinjaman = function(id) {
        deletePinjamanId = id;
        const delModal = new bootstrap.Modal(document.getElementById('deletePinjamanModal'));
        delModal.show();
    };

    window.savePinjaman = function() {
        const formData = $('#pinjamanForm').serialize();
        const isEdit = $('#pinjamanId').val() !== '';
        
        $.post('/ksp_mono/public/api/pinjaman.php?action=' + (isEdit ? 'update' : 'create'), formData)
            .done(function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('pinjamanModal'));
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

    window.confirmDeletePinjaman = function() {
        if (deletePinjamanId) {
            $.post('/ksp_mono/public/api/pinjaman.php?action=delete', { id: deletePinjamanId })
                .done(function(response) {
                    if (response.success) {
                        const delModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deletePinjamanModal'));
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
