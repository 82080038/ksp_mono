<!-- RAT Checklist (Table only) -->
<div class="tab-pane fade" id="rat" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Checklist RAT</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openRatModal()">Tambah Item</button>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Wajib?</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratChecklist as $rc): ?>
                <tr class="rat-row" data-id="<?php echo $rc['id']; ?>" data-item="<?php echo htmlspecialchars($rc['item']); ?>" data-required="<?php echo $rc['required']; ?>" data-status="<?php echo htmlspecialchars($rc['status']); ?>" data-notes="<?php echo htmlspecialchars($rc['notes']); ?>" data-order="<?php echo htmlspecialchars($rc['order_no']); ?>" style="cursor:pointer;">
                    <td><?php echo htmlspecialchars($rc['item']); ?></td>
                    <td><?php echo $rc['required'] ? 'Ya' : 'Tidak'; ?></td>
                    <td><?php echo htmlspecialchars($rc['status']); ?></td>
                    <td><?php echo htmlspecialchars($rc['notes']); ?></td>
                    <td><?php echo htmlspecialchars($rc['order_no']); ?></td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openRatModal(<?php echo $rc['id']; ?>)"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-rat" data-id="<?php echo $rc['id']; ?>"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
