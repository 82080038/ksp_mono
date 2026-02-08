<!-- Integrasi & Notifikasi (Table only) -->
<div class="tab-pane fade" id="integration" role="tabpanel">
    <?php
        $hiddenIntegration = [
            'reminder_due_days' => $integrationSettings['reminder_due_days'] ?? 3,
            'reminder_channel' => $integrationSettings['reminder_channel'] ?? 'email',
            'payment_channel' => $integrationSettings['payment_channel'] ?? 'transfer',
            'transfer_fee' => $integrationSettings['transfer_fee'] ?? 0,
            'cutoff_time' => $integrationSettings['cutoff_time'] ?? '17:00',
            'rat_reminder_days' => $integrationSettings['rat_reminder_days'] ?? 7
        ];
        foreach ($hiddenIntegration as $k => $v) {
            echo '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'">';
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Integrasi & Notifikasi</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openIntegrationModal()">Edit</button>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <tbody>
                <tr class="integration-row" data-field="reminder_due_days" style="cursor:pointer;">
                    <td>Reminder Jatuh Tempo (hari sebelum)</td>
                    <td><?php echo htmlspecialchars($integrationSettings['reminder_due_days'] ?? 3); ?> hari</td>
                </tr>
                <tr class="integration-row" data-field="reminder_channel" style="cursor:pointer;">
                    <td>Channel Reminder</td>
                    <td><?php echo htmlspecialchars($integrationSettings['reminder_channel'] ?? 'email'); ?></td>
                </tr>
                <tr class="integration-row" data-field="payment_channel" style="cursor:pointer;">
                    <td>Channel Pembayaran</td>
                    <td><?php echo htmlspecialchars($integrationSettings['payment_channel'] ?? 'transfer'); ?></td>
                </tr>
                <tr class="integration-row" data-field="transfer_fee" style="cursor:pointer;">
                    <td>Biaya Transfer</td>
                    <td><?php echo format_money($integrationSettings['transfer_fee'] ?? 0); ?></td>
                </tr>
                <tr class="integration-row" data-field="cutoff_time" style="cursor:pointer;">
                    <td>Cut-off Waktu Pembayaran</td>
                    <td><?php echo htmlspecialchars($integrationSettings['cutoff_time'] ?? '17:00'); ?></td>
                </tr>
                <tr class="integration-row" data-field="rat_reminder_days" style="cursor:pointer;">
                    <td>Reminder RAT (hari sebelum)</td>
                    <td><?php echo htmlspecialchars($integrationSettings['rat_reminder_days'] ?? 7); ?> hari</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
