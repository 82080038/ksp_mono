<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

// Check permissions
if (!has_permission('view_reports')) {
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
                <h1 class="h2">Laporan Koperasi</h1>
            </div>
            
            <!-- Report Forms will be loaded here -->
            <div id="reportFormsContainer"></div>
        </main>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Lazy load form when container is visible
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            $('#reportFormsContainer').load('/ksp_mono/pages/laporan/financial_report.php');
            observer.unobserve(entries[0].target);
        }
    });
    
    observer.observe(document.getElementById('reportFormsContainer'));
});
</script>

<script src="/ksp_mono/assets/js/utilities.js"></script>
<script src="/ksp_mono/assets/js/report_form.js"></script>
