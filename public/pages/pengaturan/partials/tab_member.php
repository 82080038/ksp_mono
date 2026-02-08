<!-- Member Settings (Table only) -->
<div class="tab-pane fade" id="member" role="tabpanel">
    <!-- Hidden inputs for member settings -->
    <?php
        $hiddenMemberFields = [
            'registration_fee' => $memberSettings['registration_fee'] ?? '',
            'min_age' => $memberSettings['min_age'] ?? 17,
            'mandatory_savings' => $memberSettings['mandatory_savings'] ?? '',
            'voluntary_savings_min' => $memberSettings['voluntary_savings_min'] ?? ''
        ];
        foreach ($hiddenMemberFields as $field => $value) {
            echo '<input type="hidden" name="'.$field.'" value="'.htmlspecialchars($value).'">';
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Aturan Keanggotaan</h6>
        <span class="text-muted small">Klik baris untuk edit</span>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <tbody>
                <tr class="member-row" data-field="registration_fee" style="cursor:pointer;">
                    <td>Biaya Pendaftaran (Rp)</td>
                    <td><?php echo format_money($memberSettings['registration_fee'] ?? 0); ?></td>
                </tr>
                <tr class="member-row" data-field="min_age" style="cursor:pointer;">
                    <td>Usia Minimum (tahun)</td>
                    <td><?php echo htmlspecialchars($memberSettings['min_age'] ?? 17); ?></td>
                </tr>
                <tr class="member-row" data-field="mandatory_savings" style="cursor:pointer;">
                    <td>Simpanan Wajib Bulanan (Rp)</td>
                    <td><?php echo format_money($memberSettings['mandatory_savings'] ?? 0); ?></td>
                </tr>
                <tr class="member-row" data-field="voluntary_savings_min" style="cursor:pointer;">
                    <td>Minimal Simpanan Sukarela (Rp)</td>
                    <td><?php echo format_money($memberSettings['voluntary_savings_min'] ?? 0); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
