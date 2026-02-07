// Report Form Validation
$(document).ready(function() {
    // Initialize form
    const $form = $('#financialReportForm');
    
    // Form validation
    $form.on('submit', function(e) {
        e.preventDefault();
        
        // Validate inputs
        const startDate = new Date($('#startDate').val());
        const endDate = new Date($('#endDate').val());
        
        if (startDate > endDate) {
            showError('Tanggal mulai harus sebelum tanggal selesai');
            return;
        }
        
        // Submit form if valid
        generateReport();
    });
});

function generateReport() {
    const formData = $('#financialReportForm').serialize();
    
    $.ajax({
        url: '/ksp_mono/api/laporan.php?action=financial',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayReport(response.data);
            } else {
                showError(response.message || 'Gagal generate laporan');
            }
        },
        error: function() {
            showError('Terjadi kesalahan saat generate laporan');
        }
    });
}

function displayReport(data) {
    // Implement report display logic
    console.log('Report data:', data);
}

function showError(message) {
    // Implement error display
    alert(message);
}
