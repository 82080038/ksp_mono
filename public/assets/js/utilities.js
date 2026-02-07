// Shared Utility Functions

function formatPhoneNumber(phone) {
    return phone.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
}

function showToast(type, message) {
    // Toast notification implementation
}

function validateIndonesianDate(dateStr) {
    return /^\d{2}-\d{2}-\d{4}$/.test(dateStr);
}

function handleAjaxError(xhr, status, error) {
    showToast('error', 'Request failed: ' + error);
}
