<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-piggy-bank"></i> Transaksi Simpanan</h5>
    </div>
    <div class="card-body">
        <form id="savingsTransactionForm">
            <!-- Transaction Type -->
            <div class="form-floating mb-3">
                <select class="form-select" name="jenis" id="transactionType" required>
                    <option value="setoran">Setoran</option>
                    <option value="penarikan">Penarikan</option>
                </select>
                <label for="transactionType"><i class="bi bi-arrow-left-right"></i> Jenis Transaksi</label>
            </div>
            
            <!-- Member Selection -->
            <div class="form-floating mb-3">
                <select class="form-select" name="anggota_id" id="memberSelect" required>
                    <option value="">-- Pilih Anggota --</option>
                    <!-- Options will be loaded via JavaScript -->
                </select>
                <label for="memberSelect"><i class="bi bi-person"></i> Anggota</label>
            </div>
            
            <!-- Amount -->
            <div class="form-floating mb-3">
                <input type="number" class="form-control" name="jumlah" id="amount" 
                    min="10000" step="1000" required placeholder="Jumlah">
                <small class="text-muted d-block mt-1">Minimal Rp 10,000</small>
                <label for="amount"><i class="bi bi-cash"></i> Jumlah</label>
            </div>
            
            <!-- Description -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="keterangan" id="description" 
                    placeholder="Keterangan">
                <label for="description"><i class="bi bi-card-text"></i> Keterangan</label>
            </div>
            
            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Proses Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
