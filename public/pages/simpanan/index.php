<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

// Check permissions
if (!has_permission('manage_savings')) {
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
                <h1 class="h2">Simpanan Anggota</h1>
            </div>
            
            <!-- Savings Form will be loaded here via JavaScript -->
            <div id="savingsFormContainer"></div>
        </main>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Load savings form
    $('#savingsFormContainer').load('/ksp_mono/pages/simpanan/savings_form.php');
    
    // Form submission handler will be added here
});
</script>

<script src="/ksp_mono/assets/js/savings_form.js"></script>
