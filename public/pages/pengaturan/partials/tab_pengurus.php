<!-- Pengurus & Pengawas (Master only) -->
<div class="tab-pane fade" id="pengurus" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Data Pengurus &amp; Pengawas</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openPengurusModal(null)">Tambah</button>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th>Jabatan</th>
                    <th>Nama</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pengurusData as $row): ?>
                <tr class="pengurus-row" data-id="<?php echo $row['id']; ?>" data-jabatan="<?php echo htmlspecialchars($row['jabatan']); ?>" data-orang="<?php echo $row['orang_id']; ?>" data-mulai="<?php echo htmlspecialchars($row['tanggal_mulai']); ?>" data-akhir="<?php echo htmlspecialchars($row['tanggal_akhir']); ?>" style="cursor:pointer;">
                    <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal_mulai']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal_akhir']); ?></td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openPengurusModal(<?php echo $row['id']; ?>)"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-pengurus" data-id="<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
