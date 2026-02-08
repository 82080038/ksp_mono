<!-- Compliance Settings (Table only) -->
<div class="tab-pane fade" id="compliance" role="tabpanel">
    <!-- Hidden inputs for compliance settings -->
    <?php
        $hiddenComplianceFields = [
            'tax_rate' => $complianceSettings['tax_rate'] ?? 17,
            'audit_required' => $complianceSettings['audit_required'] ?? '1',
            'document_retention_years' => $complianceSettings['document_retention_years'] ?? 5
        ];
        foreach ($hiddenComplianceFields as $field => $value) {
            echo '<input type="hidden" name="'.$field.'" value="'.htmlspecialchars($value).'">';
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Kebijakan Compliance</h6>
        <span class="text-muted small">Klik baris untuk edit</span>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <tbody>
                <tr class="compliance-row" data-field="tax_rate" style="cursor:pointer;">
                    <td>Pajak (PPh %)</td>
                    <td><?php echo htmlspecialchars($complianceSettings['tax_rate'] ?? 17); ?>%</td>
                </tr>
                <tr class="compliance-row" data-field="audit_required" style="cursor:pointer;">
                    <td>Audit Wajib</td>
                    <td><?php echo ($complianceSettings['audit_required'] ?? '1') == '1' ? 'Ya' : 'Tidak'; ?></td>
                </tr>
                <tr class="compliance-row" data-field="document_retention_years" style="cursor:pointer;">
                    <td>Retensi Dokumen (tahun)</td>
                    <td><?php echo htmlspecialchars($complianceSettings['document_retention_years'] ?? 5); ?> tahun</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
