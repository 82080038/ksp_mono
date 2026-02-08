<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-piggy-bank"></i> Transaksi Simpanan</h5>
    </div>
    <div class="card-body">
        <form id="savingsTransactionForm">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="transactionType" class="form-label"><i class="bi bi-arrow-left-right"></i> Jenis Transaksi</label>
                    <select class="form-select" name="jenis" id="transactionType" required>
                        <option value="setoran">Setoran</option>
                        <option value="penarikan">Penarikan</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="memberSelect" class="form-label"><i class="bi bi-person"></i> Anggota</label>
                    <select class="form-select" name="anggota_id" id="memberSelect" required>
                        <option value="">-- Pilih Anggota --</option>
                        <!-- Options will be loaded via JavaScript -->
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="amount" class="form-label"><i class="bi bi-cash"></i> Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" id="amount" 
                        min="10000" step="500" required placeholder="Jumlah">
                    <small class="text-muted">Minimal Rp 10,000</small>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-file-earmark-text"></i> Proses Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
