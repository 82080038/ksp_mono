<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Form Pengajuan Pinjaman</h5>
    </div>
    <div class="card-body">
        <form id="loanApplicationForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="anggotaSelect" class="form-label"><i class="bi bi-person"></i> Anggota</label>
                    <select class="form-select" name="anggota_id" id="anggotaSelect" required>
                        <option value="">-- Pilih Anggota --</option>
                        <!-- Options will be loaded via JavaScript -->
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="loanAmount" class="form-label"><i class="bi bi-cash"></i> Jumlah Pinjaman</label>
                    <input type="number" class="form-control" name="amount" id="loanAmount" min="100000" step="50000" required>
                    <small class="text-muted">Minimal Rp 100,000</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="loanTerm" class="form-label"><i class="bi bi-calendar"></i> Jangka Waktu</label>
                    <input type="number" class="form-control" name="term_months" id="loanTerm" min="1" max="36" required placeholder="bulan">
                    <small class="text-muted">1-36 bulan</small>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-file-earmark-text"></i> Ajukan Pinjaman
                </button>
            </div>
        </form>
    </div>
</div>
