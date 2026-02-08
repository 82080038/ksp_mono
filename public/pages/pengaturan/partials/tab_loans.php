<!-- Loans Settings -->
<div class="tab-pane fade" id="loans" role="tabpanel">
    <!-- Hidden inputs to preserve values on save -->
    <?php
        $hiddenLoanFields = [
            'loans_interest_rate' => $loansSettings['interest_rate'] ?? '0',
            'loans_max_amount' => $loansSettings['max_amount'] ?? '0',
            'loans_default_type' => $loansSettings['default_type'] ?? 'konsumtif',
            'loans_max_tenor_months' => $loansSettings['max_tenor_months'] ?? '0',
            'loans_interest_method' => $loansSettings['interest_method'] ?? 'flat',
            'loans_admin_fee' => $loansSettings['admin_fee'] ?? '0',
            'loans_provision_fee' => $loansSettings['provision_fee'] ?? '0',
            'loans_penalty_rate' => $loansSettings['penalty_rate'] ?? '0',
            'loans_max_plafon_savings_ratio' => $loansSettings['max_plafon_savings_ratio'] ?? '0',
            'loans_max_installment_income_ratio' => $loansSettings['max_installment_income_ratio'] ?? '0',
            'loans_description' => $loansSettings['description'] ?? ''
        ];
        foreach ($hiddenLoanFields as $field => $value) {
            echo '<input type="hidden" name="'.$field.'" value="'.htmlspecialchars($value).'">';
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Master Jenis Pinjaman</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openLoanTypesModal()">Tambah Jenis</button>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Bunga (%)</th>
                    <th>Metode</th>
                    <th>Tenor Maks (bln)</th>
                    <th>Plafon/Simpanan (x)</th>
                    <th>Angsuran/Penghasilan (%)</th>
                    <th>Admin (Rp)</th>
                    <th>Provisi (%)</th>
                    <th>Denda (%/hari)</th>
                    <th>Asuransi (%)</th>
                    <th>LTV (%)</th>
                    <th>Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loanTypes as $lt): ?>
                <tr class="loan-type-row" data-id="<?php echo $lt['id']; ?>" style="cursor:pointer;">
                    <td><?php echo htmlspecialchars($lt['name']); ?></td>
                    <td><?php echo htmlspecialchars($lt['interest_rate']); ?></td>
                    <td><?php echo htmlspecialchars($lt['interest_method']); ?></td>
                    <td><?php echo htmlspecialchars($lt['max_tenor_months']); ?></td>
                    <td><?php echo htmlspecialchars($lt['max_plafon_savings_ratio']); ?></td>
                    <td><?php echo htmlspecialchars($lt['max_installment_income_ratio']); ?></td>
                    <td><?php echo format_money($lt['admin_fee']); ?></td>
                    <td><?php echo htmlspecialchars($lt['provision_fee']); ?></td>
                    <td><?php echo htmlspecialchars($lt['penalty_rate']); ?></td>
                    <td><?php echo htmlspecialchars($lt['insurance_rate'] ?? 0); ?><?php echo ($lt['require_insurance'] ?? 0) ? '*' : ''; ?></td>
                    <td><?php echo htmlspecialchars($lt['ltv_ratio'] ?? 0); ?></td>
                    <td><?php echo $lt['is_active'] ? 'Ya' : 'Tidak'; ?></td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openLoanTypeModal(<?php echo $lt['id']; ?>)"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-loan" data-id="<?php echo $lt['id']; ?>"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
