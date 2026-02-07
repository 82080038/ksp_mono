<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Form Pengajuan Pinjaman</h5>
    </div>
    <div class="card-body">
        <form id="loanApplicationForm">
            <!-- Member Selection -->
            <div class="form-floating mb-3">
                <select class="form-select" name="anggota_id" id="anggotaSelect" required>
                    <option value="">-- Pilih Anggota --</option>
                    <!-- Options will be loaded via JavaScript -->
                </select>
                <label for="anggotaSelect"><i class="bi bi-person"></i> Anggota</label>
            </div>
            
            <!-- Loan Amount -->
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="amount" id="loanAmount" 
                    min="100000" step="50000" required placeholder="Jumlah Pinjaman">
                <small class="text-muted d-block mt-1">Minimal Rp 100,000</small>
                <label for="loanAmount"><i class="bi bi-currency-exchange"></i> Jumlah Pinjaman</label>
            </div>
            
            <!-- Loan Term -->
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="term_months" id="loanTerm" 
                    min="1" max="36" required placeholder="Jangka Waktu (bulan)">
                <small class="text-muted d-block mt-1">1-36 bulan</small>
                <label for="loanTerm"><i class="bi bi-calendar"></i> Jangka Waktu (bulan)</label>
            </div>
            
            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-send-check"></i> Ajukan Pinjaman
                </button>
            </div>
        </form>
    </div>
</div>
