// Loan Form Validation
$(document).ready(function() {
    // Initialize form
    const $form = $('#loanApplicationForm');
    
    // Load member options
    $.getJSON('/ksp_mono/api/anggota.php?action=list')
        .done(function(data) {
            if (data.success) {
                const $select = $('#anggotaSelect');
                data.data.forEach(member => {
                    $select.append(`<option value="${member.id}">${member.nomor_anggota} - ${member.nama}</option>`);
                });
            }
        });
    
    // Form validation
    $form.on('submit', function(e) {
        e.preventDefault();
        
        // Validate inputs using shared utilities
        const amount = parseFloat($('#loanAmount').val());
        const term = parseInt($('#loanTerm').val());
        
        if (amount < 100000) {
            showToast('error', 'Jumlah pinjaman minimal Rp 100,000');
            return;
        }
        
        if (term < 1 || term > 36) {
            showToast('error', 'Jangka waktu harus 1-36 bulan');
            return;
        }
        
        // Submit form if valid
        submitLoanApplication();
    });
    
    // Real-time validation feedback
    $('#loanAmount, #loanTerm').on('input', function() {
        $(this).removeClass('is-invalid');
    });
});

function submitLoanApplication() {
    const formData = $('#loanApplicationForm').serialize();
    
    $.ajax({
        url: '/ksp_mono/api/pinjaman.php?action=create',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast('success', 'Pengajuan pinjaman berhasil dikirim');
                $('#loanApplicationForm')[0].reset();
            } else {
                showToast('error', response.message || 'Gagal mengajukan pinjaman');
            }
        },
        error: handleAjaxError
    });
}

function showToast(type, message) {
    // Implement toast display
}

function handleAjaxError(xhr, status, error) {
    // Implement error handling
}
