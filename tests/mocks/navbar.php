<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">TEST</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (in_array('manage_cooperative', $_SESSION['permissions'] ?? [])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="?modul=coop_details">
                        <i class="bi bi-building me-1"></i> Detail Koperasi
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
