<!-- Occupations Settings -->
<div class="tab-pane fade" id="occupations" role="tabpanel">
    <h6>Pengaturan Pekerjaan Anggota</h6>
    <p class="text-muted">Pekerjaan yang diizinkan untuk anggota baru</p>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span>Status:</span>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="editPekerjaanSettings()">
            <i class="bi bi-pencil"></i> Edit
        </button>
    </div>
    <div>
        <?php if (empty($allowedOccupations)): ?>
            <span class="text-muted">Belum ada pekerjaan yang diizinkan</span>
        <?php else: ?>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($allowedOccupations as $id): 
                    $occ = array_filter($occupations, fn($o) => $o['id'] == $id);
                    $occ = reset($occ);
                ?>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($occ['nama_pekerjaan'] ?? 'Unknown'); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
