// Savings Form Validation
waitForJqueryAndRun(function() {
    // Initialize form
    const $form = $('#savingsTransactionForm');
    
    // Load member options
    $.getJSON('/ksp_mono/api/anggota.php?action=list')
        .done(function(data) {
            if (data.success) {
                const $select = $('#memberSelect');
                data.data.forEach(member => {
                    $select.append(`<option value="${member.id}">${member.nomor_anggota} - ${member.nama}</option>`);
                });
            }
        });
    
    // Form validation
    $form.on('submit', function(e) {
        e.preventDefault();
        
        // Validate inputs using shared utilities
        const amount = parseFloat($('#amount').val());
        const transactionType = $('#transactionType').val();
        
        if (amount < 10000) {
            showToast('error', 'Jumlah minimal Rp 10,000');
            return;
        }
        
        submitSavingsTransaction();
    });
    
    // Real-time validation feedback
    $('#amount').on('input', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Initialize phone field masking
    $('.phone-field').on('input', function() {
        $(this).val(formatPhoneNumber($(this).val()));
    });
});

function submitSavingsTransaction() {
    const formData = $('#savingsTransactionForm').serialize();
    
    $.ajax({
        url: '/ksp_mono/api/simpanan.php?action=create',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast('success', 'Transaksi simpanan berhasil diproses');
                $('#savingsTransactionForm')[0].reset();
            } else {
                showToast('error', response.message || 'Gagal memproses transaksi');
            }
        },
        error: handleAjaxError
    });
}

function showToast(type, message) {
    // Implement toast display
}

function formatPhoneNumber(phoneNumber) {
    // Implement phone number formatting
}

function handleAjaxError(xhr, status, error) {
    // Implement error handling
}
