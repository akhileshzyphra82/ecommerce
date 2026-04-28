<?php
require_once '../data/store_data.php';
$currentPage = 'manufacturers';
$pageTitle   = 'Manufacturers — Sinelec Tech';
require_once 'header.php';
?>

<main>
<div class="wrap page-wrap">

  <!-- Page Header -->
  <div class="page-hero">
    <div class="page-eyebrow">Authorised Distributor</div>
    <h1 class="page-title">Our Manufacturer Partners</h1>
    <p class="page-sub">We source directly from <?= count($storeData['manufacturers']) ?>+ leading semiconductor brands — every part is genuine and fully traceable.</p>
  </div>

  <!-- Manufacturer Grid -->
  <div class="mfr-grid">
    <?php foreach ($storeData['manufacturers'] as $mfr): ?>
    <a href="products?mfr=<?= urlencode($mfr['name']) ?>" class="mfr-card">
      <div class="mfr-logo-placeholder"><?= htmlspecialchars(substr($mfr['name'], 0, 2)) ?></div>
      <div class="mfr-card-name"><?= htmlspecialchars($mfr['name']) ?></div>
      <div class="mfr-card-country"><?= htmlspecialchars($mfr['country']) ?></div>
      <div class="mfr-card-products"><?= (int)$mfr['products'] ?> products</div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Why Genuine Section -->
  <div class="trust-badges mt-32">
    <div class="trust-badges-grid">
      <div>
        <div class="trust-badge-icon">🔍</div>
        <div class="trust-badge-title">Authenticity Verified</div>
        <div class="trust-badge-sub">Every part traced to source</div>
      </div>
      <div>
        <div class="trust-badge-icon">📄</div>
        <div class="trust-badge-title">Full Documentation</div>
        <div class="trust-badge-sub">Datasheets &amp; CoC on request</div>
      </div>
      <div>
        <div class="trust-badge-icon">🤝</div>
        <div class="trust-badge-title">Authorised Partner</div>
        <div class="trust-badge-sub">Direct manufacturer relationships</div>
      </div>
      <div>
        <div class="trust-badge-icon">🚫</div>
        <div class="trust-badge-title">Zero Counterfeits</div>
        <div class="trust-badge-sub">100% grey-market free</div>
      </div>
    </div>
  </div>

</div>
</main>

<?php require_once 'footer.php'; ?>
