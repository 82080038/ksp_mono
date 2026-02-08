<!-- General Settings (Table only) -->
<div class="tab-pane fade show active" id="general" role="tabpanel">
    <!-- Hidden inputs for general settings -->
    <?php
        $hiddenGeneralFields = [
            'nama_koperasi' => $cooperative['nama_koperasi'] ?? '',
            'jenis_koperasi' => $jenisKoperasiDisplay ?? '',
            'npwp' => $cooperative['npwp'] ?? '',
            'nib' => $cooperative['nib'] ?? '',
            'alamat_legal' => $cooperative['alamat_legal'] ?? '',
            'modal_pokok' => $cooperative['modal_pokok'] ?? 0,
            'simpanan_pokok_total' => $cooperative['simpanan_pokok_total'] ?? 0,
            'rat_terakhir' => $cooperative['rat_terakhir'] ?? '',
            'laporan_tahunan_terakhir' => $cooperative['laporan_tahunan_terakhir'] ?? '',
            'dewan_pengawas_count' => $cooperative['dewan_pengawas_count'] ?? 0,
            'dewan_pengurus_count' => $cooperative['dewan_pengurus_count'] ?? 0,
            'anggota_count' => $cooperative['anggota_count'] ?? 0,
            'rencana_kerja_3tahun' => $cooperative['rencana_kerja_3tahun'] ?? '',
            'pernyataan_admin' => $cooperative['pernyataan_admin'] ?? '',
            'daftar_sarana' => $cooperative['daftar_sarana'] ?? ''
        ];
        foreach ($hiddenGeneralFields as $field => $value) {
            echo '<input type="hidden" name="'.$field.'" value="'.htmlspecialchars($value).'">';
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        <h6 class="mb-0">Atribut Koperasi</h6>
        <span class="text-muted small">Klik baris untuk edit</span>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-sm table-striped align-middle">
            <tbody>
                <tr class="general-row" data-field="nama_koperasi" style="cursor:pointer;">
                    <td>Nama Koperasi</td>
                    <td><?php echo htmlspecialchars($cooperative['nama_koperasi'] ?? '-'); ?></td>
                </tr>
                <tr class="general-row" data-field="jenis_koperasi" style="cursor:pointer;">
                    <td>Jenis Koperasi</td>
                    <td><?php echo htmlspecialchars($jenisKoperasiDisplay ?? '-'); ?></td>
                </tr>
                <tr class="general-row" data-field="npwp" style="cursor:pointer;">
                    <td>NPWP</td>
                    <td><?php echo htmlspecialchars($cooperative['npwp'] ?? '-'); ?></td>
                </tr>
                <tr class="general-row" data-field="nib" style="cursor:pointer;">
                    <td>NIB</td>
                    <td><?php echo htmlspecialchars($cooperative['nib'] ?? '-'); ?></td>
                </tr>
                <tr class="general-row" data-field="alamat_legal" style="cursor:pointer;">
                    <td>Alamat Legal</td>
                    <td><?php echo htmlspecialchars($cooperative['alamat_legal'] ?? '-'); ?></td>
                </tr>
                <tr class="general-row" data-field="modal_pokok" style="cursor:pointer;">
                    <td>Modal Pokok</td>
                    <td><?php echo format_money($cooperative['modal_pokok'] ?? 0); ?></td>
                </tr>
                <tr class="general-row" data-field="simpanan_pokok_total" style="cursor:pointer;">
                    <td>Total Simpanan Pokok</td>
                    <td><?php echo format_money($cooperative['simpanan_pokok_total'] ?? 0); ?></td>
                </tr>
                <tr class="general-row" data-field="rat_terakhir" style="cursor:pointer;">
                    <td>RAT Terakhir</td>
                    <td><?php echo htmlspecialchars($cooperative['rat_terakhir'] ?? '-'); ?></td>
                </tr>
                <tr class="general-row" data-field="laporan_tahunan_terakhir" style="cursor:pointer;">
                    <td>Laporan Tahunan Terakhir</td>
                    <td><?php echo htmlspecialchars($cooperative['laporan_tahunan_terakhir'] ?? '-'); ?></td>
                </tr>
                <tr class="general-row" data-field="dewan_pengawas_count" style="cursor:pointer;">
                    <td>Jumlah Dewan Pengawas</td>
                    <td><?php echo htmlspecialchars($cooperative['dewan_pengawas_count'] ?? 0); ?></td>
                </tr>
                <tr class="general-row" data-field="dewan_pengurus_count" style="cursor:pointer;">
                    <td>Jumlah Dewan Pengurus</td>
                    <td><?php echo htmlspecialchars($cooperative['dewan_pengurus_count'] ?? 0); ?></td>
                </tr>
                <tr class="general-row" data-field="anggota_count" style="cursor:pointer;">
                    <td>Jumlah Anggota</td>
                    <td><?php echo htmlspecialchars($cooperative['anggota_count'] ?? 0); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
        <h6 class="mb-0">Dokumen Koperasi</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openGeneralModal()">Upload/Edit Dokumen</button>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th>Dokumen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Akta Pendirian</td>
                    <td><?php echo (!empty($cooperative['akta_pendirian'])) ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-secondary">Belum Upload</span>'; ?></td>
                    <td>
                        <?php if (!empty($cooperative['akta_pendirian'])): ?>
                            <a href="/ksp_mono/public/uploads/documents/<?php echo htmlspecialchars($cooperative['akta_pendirian']); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>AD/ART</td>
                    <td><?php echo (!empty($cooperative['ad_art'])) ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-secondary">Belum Upload</span>'; ?></td>
                    <td>
                        <?php if (!empty($cooperative['ad_art'])): ?>
                            <a href="/ksp_mono/public/uploads/documents/<?php echo htmlspecialchars($cooperative['ad_art']); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Berita Acara Rapat</td>
                    <td><?php echo (!empty($cooperative['berita_acara_rapat'])) ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-secondary">Belum Upload</span>'; ?></td>
                    <td>
                        <?php if (!empty($cooperative['berita_acara_rapat'])): ?>
                            <a href="/ksp_mono/public/uploads/documents/<?php echo htmlspecialchars($cooperative['berita_acara_rapat']); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Rencana Kegiatan</td>
                    <td><?php echo (!empty($cooperative['rencana_kegiatan'])) ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-secondary">Belum Upload</span>'; ?></td>
                    <td>
                        <?php if (!empty($cooperative['rencana_kegiatan'])): ?>
                            <a href="/ksp_mono/public/uploads/documents/<?php echo htmlspecialchars($cooperative['rencana_kegiatan']); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if (strpos(strtolower($jenisKoperasiDisplay ?? ''), 'simpan pinjam') !== false): ?>
                <tr>
                    <td>Rencana Kerja 3 Tahun</td>
                    <td><?php echo (!empty($cooperative['rencana_kerja_3tahun'])) ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-secondary">Belum Upload</span>'; ?></td>
                    <td>
                        <?php if (!empty($cooperative['rencana_kerja_3tahun'])): ?>
                            <a href="/ksp_mono/public/uploads/documents/<?php echo htmlspecialchars($cooperative['rencana_kerja_3tahun']); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pernyataan Administrasi</td>
                    <td><?php echo (!empty($cooperative['pernyataan_admin'])) ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-secondary">Belum Upload</span>'; ?></td>
                    <td>
                        <?php if (!empty($cooperative['pernyataan_admin'])): ?>
                            <a href="/ksp_mono/public/uploads/documents/<?php echo htmlspecialchars($cooperative['pernyataan_admin']); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Daftar Sarana Kerja</td>
                    <td><?php echo (!empty($cooperative['daftar_sarana'])) ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-secondary">Belum Upload</span>'; ?></td>
                    <td>
                        <?php if (!empty($cooperative['daftar_sarana'])): ?>
                            <a href="/ksp_mono/public/uploads/documents/<?php echo htmlspecialchars($cooperative['daftar_sarana']); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
