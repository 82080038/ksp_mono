<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

// Check permissions
if (!has_permission('manage_loans')) {
    header('Location: /dashboard.php');
    exit;
}

// Include header and navbar
require __DIR__ . '/../../layouts/header.php';
require __DIR__ . '/../../partials/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require __DIR__ . '/../../partials/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Pengajuan Pinjaman</h1>
            </div>
            
            <!-- Loan Application Form will be loaded here via JavaScript -->
            <div id="loanFormContainer"></div>
        </main>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Load loan form
    $('#loanFormContainer').load('/ksp_mono/pages/pinjaman/loan_form.php');
    
    // Form submission handler will be added here
});
</script>

<script src="/ksp_mono/assets/js/loan_form.js"></script>
