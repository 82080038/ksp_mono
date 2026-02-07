<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Laporan Keuangan</h5>
    </div>
    <div class="card-body">
        <form id="financialReportForm">
            <!-- Date Range -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="date" class="form-control" name="start_date" id="startDate" required>
                        <label for="startDate">Tanggal Mulai</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="date" class="form-control" name="end_date" id="endDate" required>
                        <label for="endDate">Tanggal Selesai</label>
                    </div>
                </div>
            </div>
            
            <!-- Report Type -->
            <div class="form-floating mb-3">
                <select class="form-select" name="report_type" id="reportType" required>
                    <option value="income">Laporan Pendapatan</option>
                    <option value="expense">Laporan Pengeluaran</option>
                    <option value="all">Laporan Lengkap</option>
                </select>
                <label for="reportType">Jenis Laporan</label>
            </div>
            
            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-file-earmark-text"></i> Generate Laporan
                </button>
            </div>
        </form>
    </div>
</div>
