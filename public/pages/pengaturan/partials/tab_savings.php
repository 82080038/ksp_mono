<!-- Savings Settings (Master only) -->
<div class="tab-pane fade" id="savings" role="tabpanel">
    <?php
        $hiddenSavingsFields = [
            'savings_interest_rate' => $savingsSettings['interest_rate'] ?? '0',
            'savings_min_deposit' => $savingsSettings['min_deposit'] ?? '0',
            'savings_description' => $savingsSettings['description'] ?? ''
        ];
        foreach ($hiddenSavingsFields as $field => $value) {
            echo '<input type="hidden" name="'.$field.'" value="'.htmlspecialchars($value).'">';
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Master Jenis Simpanan</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openSavingsTypesModal()">Tambah Jenis</button>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Bunga (%)</th>
                    <th>Min Setoran</th>
                    <th>Biaya Admin</th>
                    <th>Denda (%/hari)</th>
                    <th>Lock (hari)</th>
                    <th>Biaya Tarik Awal (%)</th>
                    <th>Saldo Min (Rp)</th>
                    <th>Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($savingsTypes as $st): ?>
                <tr class="savings-type-row" data-id="<?php echo $st['id']; ?>" style="cursor:pointer;">
                    <td><?php echo htmlspecialchars($st['name']); ?></td>
                    <td><?php echo htmlspecialchars($st['interest_rate']); ?></td>
                    <td><?php echo format_money($st['min_deposit'] ?? 0); ?></td>
                    <td><?php echo format_money($st['admin_fee'] ?? 0); ?></td>
                    <td><?php echo htmlspecialchars($st['penalty_rate']); ?></td>
                    <td><?php echo htmlspecialchars($st['lock_period_days'] ?? 0); ?></td>
                    <td><?php echo htmlspecialchars($st['early_withdraw_fee'] ?? 0); ?></td>
                    <td><?php echo format_money($st['min_balance'] ?? 0); ?></td>
                    <td><?php echo $st['is_active'] ? 'Ya' : 'Tidak'; ?></td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openSavingsTypeModal(<?php echo $st['id']; ?>)"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-savings" data-id="<?php echo $st['id']; ?>"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
