<!-- Reports Settings (Table only) -->
<div class="tab-pane fade" id="reports" role="tabpanel">
    <!-- Hidden inputs for reports settings -->
    <?php
        $hiddenReportsFields = [
            'default_period' => $reportsSettings['default_period'] ?? 'monthly',
            'auto_generate' => $reportsSettings['auto_generate'] ?? '0',
            'description' => $reportsSettings['description'] ?? ''
        ];
        foreach ($hiddenReportsFields as $field => $value) {
            echo '<input type="hidden" name="reports_'.$field.'" value="'.htmlspecialchars($value).'">';
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Pengaturan Laporan</h6>
        <span class="text-muted small">Klik baris untuk edit</span>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <tbody>
                <tr class="reports-row" data-field="default_period" style="cursor:pointer;">
                    <td>Periode Default</td>
                    <td><?php echo ucfirst($reportsSettings['default_period'] ?? 'monthly'); ?></td>
                </tr>
                <tr class="reports-row" data-field="auto_generate" style="cursor:pointer;">
                    <td>Auto Generate</td>
                    <td><?php echo ($reportsSettings['auto_generate'] ?? '0') == '1' ? 'Ya' : 'Tidak'; ?></td>
                </tr>
                <tr class="reports-row" data-field="description" style="cursor:pointer;">
                    <td>Deskripsi</td>
                    <td><?php echo htmlspecialchars($reportsSettings['description'] ?? '-'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
