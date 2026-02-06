<?php
// Partial navigasi utama
$pageActive = $pageActive ?? '';
$navItems = [
    'anggota' => ['label' => 'Anggota', 'href' => 'dashboard.php?page=anggota#panelAnggota'],
    'simpanan' => ['label' => 'Simpanan', 'href' => 'dashboard.php?page=simpanan#panelSimpanan'],
    'pinjaman' => ['label' => 'Pinjaman', 'href' => 'dashboard.php?page=pinjaman#panelPinjaman'],
    'laporan' => ['label' => 'Laporan', 'href' => 'dashboard.php?page=laporan#sectionLaporan'],
    'pengaturan' => ['label' => 'Pengaturan', 'href' => 'dashboard.php?page=pengaturan#sectionPengaturan'],
];
?>
<div class="bg-white border-bottom">
  <div class="container py-2">
    <ul class="nav nav-pills gap-2 flex-wrap small fw-semibold">
      <?php foreach ($navItems as $key => $item): ?>
        <li class="nav-item">
          <a class="nav-link <?php echo $pageActive === $key ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($item['href']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
