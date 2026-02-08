<?php
require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/helpers.php';

// Check permissions
if (!has_permission('admin_access')) {
    echo '<div class="alert alert-danger">Anda tidak memiliki izin untuk mengakses halaman ini.</div>';
    exit;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengaturan Sistem</h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pengaturan Koperasi</h5>
            </div>
            <div class="card-body">
                <?php include __DIR__ . '/settings_form.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php
switch ($_GET['action'] ?? '') {
    case 'save':
        // Handle save settings
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_koperasi = trim($_POST['nama_koperasi'] ?? '');
            $jenis_koperasi = trim($_POST['jenis_koperasi'] ?? '');
            $npwp = trim($_POST['npwp'] ?? '');
            $nib = trim($_POST['nib'] ?? '');
            $alamat_legal = trim($_POST['alamat_legal'] ?? '');
            $akta_pendirian = trim($_POST['akta_pendirian'] ?? '');
            $ad_art = trim($_POST['ad_art'] ?? '');
            $berita_acara_rapat = trim($_POST['berita_acara_rapat'] ?? '');
            $rencana_kegiatan = trim($_POST['rencana_kegiatan'] ?? '');
            
            // Handle file uploads
            $uploadDir = __DIR__ . '/../../../public/uploads/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Function to handle file upload
            $handleFileUpload = function($fieldName, $currentValue) use ($uploadDir, $cooperative) {
                if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fieldName];
                    
                    // Validate PDF
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    
                    if ($mimeType !== 'application/pdf') {
                        throw new Exception("File {$fieldName} harus berformat PDF");
                    }
                    
                    // Check file size (max 5MB)
                    if ($file['size'] > 5 * 1024 * 1024) {
                        throw new Exception("File {$fieldName} terlalu besar, maksimal 5MB");
                    }
                    
                    // Generate unique filename
                    $filename = $fieldName . '_' . $cooperative['id'] . '_' . time() . '.pdf';
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        return $filename;
                    } else {
                        throw new Exception("Gagal upload file {$fieldName}");
                    }
                }
                return $currentValue;
            };
            
            $rencana_kerja_3tahun = $handleFileUpload('rencana_kerja_3tahun', $rencana_kerja_3tahun);
            $pernyataan_admin = $handleFileUpload('pernyataan_admin', $pernyataan_admin);
            $daftar_sarana = $handleFileUpload('daftar_sarana', $daftar_sarana);
            $dewan_pengawas_count = (int)($_POST['dewan_pengawas_count'] ?? 0);
            $dewan_pengurus_count = (int)($_POST['dewan_pengurus_count'] ?? 0);
            $anggota_count = (int)($_POST['anggota_count'] ?? 0);
            $simpanan_pokok_total = (float)($_POST['simpanan_pokok_total'] ?? 0);
            $rat_terakhir = $_POST['rat_terakhir'] ?? null;
            $laporan_tahunan_terakhir = $_POST['laporan_tahunan_terakhir'] ?? null;
            $rencana_kerja_3tahun = trim($_POST['rencana_kerja_3tahun'] ?? '');
            $pernyataan_admin = trim($_POST['pernyataan_admin'] ?? '');
            $daftar_sarana = trim($_POST['daftar_sarana'] ?? '');
            
            // Get current cooperative
            $cooperative = $_SESSION['cooperatives'][0] ?? [];
            
            if (empty($cooperative)) {
                echo '<div class="alert alert-danger">Data koperasi tidak ditemukan</div>';
                break;
            }
            
            try {
                $db = Database::conn();
                
                // Collect settings
                $savings_settings = [
                    'interest_rate' => $_POST['savings_interest_rate'] ?? '',
                    'min_deposit' => $_POST['savings_min_deposit'] ?? '',
                    'description' => $_POST['savings_description'] ?? '',
                    'admin_fee' => $_POST['savings_admin_fee'] ?? '',
                    'provision_fee' => $_POST['savings_provision_fee'] ?? ''
                ];
                $loans_settings = [
                    'interest_rate' => $_POST['loans_interest_rate'] ?? '',
                    'max_amount' => $_POST['loans_max_amount'] ?? '',
                    'description' => $_POST['loans_description'] ?? '',
                    'admin_fee' => $_POST['loans_admin_fee'] ?? '',
                    'provision_fee' => $_POST['loans_provision_fee'] ?? '',
                    'penalty_rate' => $_POST['loans_penalty_rate'] ?? '',
                    'interest_method' => $_POST['loans_interest_method'] ?? 'flat',
                    'default_type' => $_POST['loans_default_type'] ?? 'konsumtif',
                    'max_tenor_months' => $_POST['loans_max_tenor_months'] ?? '',
                    'max_plafon_savings_ratio' => $_POST['loans_max_plafon_savings_ratio'] ?? '',
                    'max_installment_income_ratio' => $_POST['loans_max_installment_income_ratio'] ?? '',
                    'loan_type_id' => $_POST['loan_type_id'] ?? '',
                    'loan_type_name' => $_POST['loan_type_name'] ?? ''
                ];
                $member_settings = [
                    'registration_fee' => $_POST['registration_fee'] ?? '',
                    'min_age' => $_POST['min_age'] ?? 17,
                    'mandatory_savings' => $_POST['mandatory_savings'] ?? '',
                    'voluntary_savings_min' => $_POST['voluntary_savings_min'] ?? ''
                ];
                $compliance_settings = [
                    'tax_rate' => $_POST['tax_rate'] ?? 17,
                    'audit_required' => $_POST['audit_required'] ?? '1',
                    'document_retention_years' => $_POST['document_retention_years'] ?? 5
                ];
                $reports_settings = [
                    'default_period' => $_POST['reports_default_period'] ?? 'monthly',
                    'auto_generate' => $_POST['reports_auto_generate'] ?? '0',
                    'description' => $_POST['reports_description'] ?? ''
                ];
                $integration_settings = [
                    'reminder_due_days' => $_POST['reminder_due_days'] ?? 3,
                    'reminder_channel' => $_POST['reminder_channel'] ?? 'email',
                    'payment_channel' => $_POST['payment_channel'] ?? 'transfer',
                    'transfer_fee' => $_POST['transfer_fee'] ?? 0,
                    'cutoff_time' => $_POST['cutoff_time'] ?? '17:00',
                    'rat_reminder_days' => $_POST['rat_reminder_days'] ?? 7
                ];
                
                // Update cooperative data
                $stmt = $db->prepare('UPDATE koperasi_tenant SET nama_koperasi = ?, jenis_koperasi = ?, npwp = ?, nib = ?, alamat_legal = ?, akta_pendirian = ?, ad_art = ?, berita_acara_rapat = ?, rencana_kegiatan = ?, dewan_pengawas_count = ?, dewan_pengurus_count = ?, anggota_count = ?, simpanan_pokok_total = ?, rat_terakhir = ?, laporan_tahunan_terakhir = ?, rencana_kerja_3tahun = ?, pernyataan_admin = ?, daftar_sarana = ?, savings_settings = ?, loans_settings = ?, member_settings = ?, compliance_settings = ?, reports_settings = ?, integration_settings = ? WHERE id = ?');
                $stmt->execute([
                    $nama_koperasi, $jenis_koperasi, $npwp, $nib, $alamat_legal, 
                    $akta_pendirian, $ad_art, $berita_acara_rapat, $rencana_kegiatan,
                    $dewan_pengawas_count, $dewan_pengurus_count,
                    $anggota_count, $simpanan_pokok_total, $rat_terakhir, $laporan_tahunan_terakhir,
                    $rencana_kerja_3tahun, $pernyataan_admin, $daftar_sarana,
                    json_encode($savings_settings), 
                    json_encode($loans_settings),
                    json_encode($member_settings),
                    json_encode($compliance_settings),
                    json_encode($reports_settings), 
                    json_encode($integration_settings), 
                    $cooperative['id']
                ]);
                
                // Update session
                $_SESSION['cooperatives'][0] = array_merge($cooperative, [
                    'nama_koperasi' => $nama_koperasi,
                    'jenis_koperasi' => $jenis_koperasi,
                    'npwp' => $npwp,
                    'nib' => $nib,
                    'alamat_legal' => $alamat_legal,
                    'akta_pendirian' => $akta_pendirian,
                    'ad_art' => $ad_art,
                    'berita_acara_rapat' => $berita_acara_rapat,
                    'rencana_kegiatan' => $rencana_kegiatan,
                    'dewan_pengawas_count' => $dewan_pengawas_count,
                    'dewan_pengurus_count' => $dewan_pengurus_count,
                    'ketua_orang_id' => $ketua_orang_id,
                    'sekretaris_orang_id' => $sekretaris_orang_id,
                    'anggota_count' => $anggota_count,
                    'simpanan_pokok_total' => $simpanan_pokok_total,
                    'rat_terakhir' => $rat_terakhir,
                    'laporan_tahunan_terakhir' => $laporan_tahunan_terakhir,
                    'rencana_kerja_3tahun' => $rencana_kerja_3tahun,
                    'pernyataan_admin' => $pernyataan_admin,
                    'daftar_sarana' => $daftar_sarana,
                    'savings_settings' => json_encode($savings_settings),
                    'loans_settings' => json_encode($loans_settings),
                    'member_settings' => json_encode($member_settings),
                    'compliance_settings' => json_encode($compliance_settings),
                    'reports_settings' => json_encode($reports_settings),
                    'integration_settings' => json_encode($integration_settings)
                ]);
                
                // Handle pengurus updates
                $ketua_orang_id = (int)($_POST['ketua_orang_id'] ?? 0);
                $sekretaris_orang_id = (int)($_POST['sekretaris_orang_id'] ?? 0);
                
                if ($ketua_orang_id) {
                    // End current ketua if exists
                    $db->prepare('UPDATE koperasi_pengurus SET tanggal_akhir = CURDATE() WHERE koperasi_tenant_id = ? AND jabatan = ? AND tanggal_akhir IS NULL')->execute([$cooperative['id'], 'ketua']);
                    // Insert new ketua
                    $db->prepare('INSERT INTO koperasi_pengurus (koperasi_tenant_id, jabatan, orang_id, tanggal_mulai) VALUES (?, ?, ?, CURDATE())')->execute([$cooperative['id'], 'ketua', $ketua_orang_id]);
                }
                
                if ($sekretaris_orang_id) {
                    // End current sekretaris if exists
                    $db->prepare('UPDATE koperasi_pengurus SET tanggal_akhir = CURDATE() WHERE koperasi_tenant_id = ? AND jabatan = ? AND tanggal_akhir IS NULL')->execute([$cooperative['id'], 'sekretaris']);
                    // Insert new sekretaris
                    $db->prepare('INSERT INTO koperasi_pengurus (koperasi_tenant_id, jabatan, orang_id, tanggal_mulai) VALUES (?, ?, ?, CURDATE())')->execute([$cooperative['id'], 'sekretaris', $sekretaris_orang_id]);
                }
                
                echo '<div class="alert alert-success">Pengaturan berhasil disimpan</div>';
                echo '<a href="?modul=pengaturan" class="btn btn-primary">Kembali</a>';
                
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Method tidak valid</div>';
        }
        break;
    case 'save_pekerjaan':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $allowed = $_POST['allowed_occupations'] ?? [];
            $allowed_json = json_encode($allowed);
            
            $db = Database::conn();
            $stmt = $db->prepare('UPDATE koperasi_tenant SET allowed_occupations = ? WHERE id = 1');
            $stmt->execute([$allowed_json]);
            
            // Update session
            $_SESSION['cooperatives'][0]['allowed_occupations'] = $allowed_json;
            
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'save_loan_type':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::conn();
            $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
            $name = trim($_POST['name'] ?? '');
            $interest_rate = (float)($_POST['interest_rate'] ?? 0);
            $interest_method = $_POST['interest_method'] === 'menurun' ? 'menurun' : 'flat';
            $max_tenor_months = (int)($_POST['max_tenor_months'] ?? 0);
            $max_plafon_savings_ratio = (float)($_POST['max_plafon_savings_ratio'] ?? 0);
            $max_installment_income_ratio = (float)($_POST['max_installment_income_ratio'] ?? 0);
            $admin_fee = (float)($_POST['admin_fee'] ?? 0);
            $provision_fee = (float)($_POST['provision_fee'] ?? 0);
            $penalty_rate = (float)($_POST['penalty_rate'] ?? 0);
            $insurance_rate = (float)($_POST['insurance_rate'] ?? 0);
            $require_insurance = isset($_POST['require_insurance']) ? 1 : 0;
            $ltv_ratio = (float)($_POST['ltv_ratio'] ?? 0);
            $collateral_type = trim($_POST['collateral_type'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

            if ($name === '') {
                echo json_encode(['success' => false, 'message' => 'Nama tidak boleh kosong']);
                break;
            }

            if ($id) {
                $stmt = $db->prepare('UPDATE loan_types SET name=?, interest_rate=?, interest_method=?, max_tenor_months=?, max_plafon_savings_ratio=?, max_installment_income_ratio=?, admin_fee=?, provision_fee=?, penalty_rate=?, insurance_rate=?, require_insurance=?, ltv_ratio=?, collateral_type=?, description=?, is_active=? WHERE id=?');
                $stmt->execute([$name, $interest_rate, $interest_method, $max_tenor_months, $max_plafon_savings_ratio, $max_installment_income_ratio, $admin_fee, $provision_fee, $penalty_rate, $insurance_rate, $require_insurance, $ltv_ratio, $collateral_type, $description, $is_active, $id]);
            } else {
                $stmt = $db->prepare('INSERT INTO loan_types (name, interest_rate, interest_method, max_tenor_months, max_plafon_savings_ratio, max_installment_income_ratio, admin_fee, provision_fee, penalty_rate, insurance_rate, require_insurance, ltv_ratio, collateral_type, description, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
                $stmt->execute([$name, $interest_rate, $interest_method, $max_tenor_months, $max_plafon_savings_ratio, $max_installment_income_ratio, $admin_fee, $provision_fee, $penalty_rate, $insurance_rate, $require_insurance, $ltv_ratio, $collateral_type, $description, $is_active]);
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'delete_loan_type':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
                break;
            }
            $db = Database::conn();
            $stmt = $db->prepare('DELETE FROM loan_types WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'save_savings_type':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::conn();
            $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
            $name = trim($_POST['name'] ?? '');
            $interest_rate = (float)($_POST['interest_rate'] ?? 0);
            $min_deposit = (float)($_POST['min_deposit'] ?? 0);
            $admin_fee = (float)($_POST['admin_fee'] ?? 0);
            $penalty_rate = (float)($_POST['penalty_rate'] ?? 0);
            $lock_period_days = (int)($_POST['lock_period_days'] ?? 0);
            $early_withdraw_fee = (float)($_POST['early_withdraw_fee'] ?? 0);
            $min_balance = (float)($_POST['min_balance'] ?? 0);
            $description = trim($_POST['description'] ?? '');
            $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

            if ($name === '') {
                echo json_encode(['success' => false, 'message' => 'Nama tidak boleh kosong']);
                break;
            }

            if ($id) {
                $stmt = $db->prepare('UPDATE savings_types SET name=?, interest_rate=?, min_deposit=?, admin_fee=?, penalty_rate=?, lock_period_days=?, early_withdraw_fee=?, min_balance=?, description=?, is_active=? WHERE id=?');
                $stmt->execute([$name, $interest_rate, $min_deposit, $admin_fee, $penalty_rate, $lock_period_days, $early_withdraw_fee, $min_balance, $description, $is_active, $id]);
            } else {
                $stmt = $db->prepare('INSERT INTO savings_types (name, interest_rate, min_deposit, admin_fee, penalty_rate, lock_period_days, early_withdraw_fee, min_balance, description, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
                $stmt->execute([$name, $interest_rate, $min_deposit, $admin_fee, $penalty_rate, $lock_period_days, $early_withdraw_fee, $min_balance, $description, $is_active]);
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'delete_savings_type':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
                break;
            }
            $db = Database::conn();
            $stmt = $db->prepare('DELETE FROM savings_types WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'save_pengurus':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::conn();
            $cooperative = $_SESSION['cooperatives'][0] ?? [];
            $tenantId = $cooperative['id'] ?? 0;
            if (!$tenantId) {
                echo json_encode(['success' => false, 'message' => 'Tenant tidak valid']);
                break;
            }
            $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
            $jabatan = trim($_POST['jabatan'] ?? '');
            $orang_id = (int)($_POST['orang_id'] ?? 0);
            $tanggal_mulai = $_POST['tanggal_mulai'] ?? null;
            $tanggal_akhir = $_POST['tanggal_akhir'] ?? null;
            if ($jabatan === '' || $orang_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Jabatan dan orang wajib diisi']);
                break;
            }
            if ($id) {
                $stmt = $db->prepare('UPDATE koperasi_pengurus SET jabatan=?, orang_id=?, tanggal_mulai=?, tanggal_akhir=? WHERE id=? AND koperasi_tenant_id=?');
                $stmt->execute([$jabatan, $orang_id, $tanggal_mulai ?: null, $tanggal_akhir ?: null, $id, $tenantId]);
            } else {
                $stmt = $db->prepare('INSERT INTO koperasi_pengurus (koperasi_tenant_id, jabatan, orang_id, tanggal_mulai, tanggal_akhir) VALUES (?,?,?,?,?)');
                $stmt->execute([$tenantId, $jabatan, $orang_id, $tanggal_mulai ?: null, $tanggal_akhir ?: null]);
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'delete_pengurus':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
                break;
            }
            $db = Database::conn();
            $stmt = $db->prepare('DELETE FROM koperasi_pengurus WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'save_integration':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::conn();
            $stmt = $db->prepare('UPDATE integration_settings SET reminder_due_days=?, reminder_channel=?, payment_channel=?, transfer_fee=?, cutoff_time=?, rat_reminder_days=? WHERE id=1');
            $stmt->execute([
                (int)($_POST['reminder_due_days'] ?? 3),
                trim($_POST['reminder_channel'] ?? 'email'),
                trim($_POST['payment_channel'] ?? 'transfer'),
                (float)($_POST['transfer_fee'] ?? 0),
                trim($_POST['cutoff_time'] ?? '17:00'),
                (int)($_POST['rat_reminder_days'] ?? 7)
            ]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        break;
    case 'save_rat_item':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::conn();
            $tenantId = $_SESSION['cooperatives'][0]['id'] ?? 0;
            if (!$tenantId) { echo json_encode(['success'=>false,'message'=>'Tenant tidak valid']); break; }
            $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
            $item = trim($_POST['item'] ?? '');
            $required = isset($_POST['required']) ? 1 : 0;
            $status = $_POST['status'] === 'done' ? 'done' : 'pending';
            $notes = trim($_POST['notes'] ?? '');
            $order_no = (int)($_POST['order_no'] ?? 0);
            if ($item === '') { echo json_encode(['success'=>false,'message'=>'Item wajib diisi']); break; }
            if ($id) {
                $stmt = $db->prepare('UPDATE rat_checklist SET item=?, required=?, status=?, notes=?, order_no=? WHERE id=? AND koperasi_tenant_id=?');
                $stmt->execute([$item, $required, $status, $notes, $order_no, $id, $tenantId]);
            } else {
                $stmt = $db->prepare('INSERT INTO rat_checklist (koperasi_tenant_id, item, required, status, notes, order_no) VALUES (?,?,?,?,?,?)');
                $stmt->execute([$tenantId, $item, $required, $status, $notes, $order_no]);
            }
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Method not allowed']);
        }
        break;
    case 'delete_rat_item':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'ID tidak valid']); break; }
            $db = Database::conn();
            $tenantId = $_SESSION['cooperatives'][0]['id'] ?? 0;
            if (!$tenantId) { echo json_encode(['success'=>false,'message'=>'Tenant tidak valid']); break; }
            $stmt = $db->prepare('DELETE FROM rat_checklist WHERE id = ? AND koperasi_tenant_id = ?');
            $stmt->execute([$id, $tenantId]);
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Method not allowed']);
        }
        break;
    case 'edit':
        // Tampilkan form edit anggota
        include 'form_edit.php';
        break;
}
?>
