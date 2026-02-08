<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Laporan Keuangan</h5>
    </div>
    <div class="card-body">
        <form id="financialReportForm">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="startDate" class="form-label"><i class="bi bi-calendar"></i> Tanggal Mulai</label>
                    <input type="text" class="form-control date-picker" id="startDate" name="start_date" placeholder="dd-mm-yyyy" value="<?php echo format_date_input($_GET['start_date'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="endDate" class="form-label"><i class="bi bi-calendar-check"></i> Tanggal Selesai</label>
                    <input type="text" class="form-control date-picker" id="endDate" name="end_date" placeholder="dd-mm-yyyy" value="<?php echo format_date_input($_GET['end_date'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="reportType" class="form-label"><i class="bi bi-file-earmark-bar-graph"></i> Jenis Laporan</label>
                    <select class="form-select" name="report_type" id="reportType" required>
                        <option value="income">Laporan Pendapatan</option>
                        <option value="expense">Laporan Pengeluaran</option>
                        <option value="all">Laporan Lengkap</option>
                    </select>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-file-earmark-text"></i> Generate Laporan
                </button>
            </div>
        </form>
    </div>
</div>
