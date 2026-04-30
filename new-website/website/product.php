<?php
require_once '../data/store_data.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$product = null;
foreach ($storeData['products'] as $p) {
    if ((int)$p['id'] === $productId) { $product = $p; break; }
}

if (!$product) {
    header('HTTP/1.1 404 Not Found');
    $currentPage = 'products';
    $pageTitle   = 'Product Not Found — Sinelec Tech';
    require_once 'header.php';
    ?>
    <main>
      <div class="wrap page-wrap">
        <div class="page-hero">
          <h1 class="page-title">Product Not Found</h1>
          <p class="page-sub">The product you're looking for doesn't exist or may have been removed.</p>
          <a href="products" class="btn btn-blue">Browse All Products</a>
        </div>
      </div>
    </main>
    <?php
    require_once 'footer.php';
    exit;
}

$pageTitle   = htmlspecialchars($product['name']) . ' — Sinelec Tech';

$currentPage = 'product';
$basePrice = $product['priceBreaks'][0]['price'] ?? ($product['price'] ?? 0);
$oldPrice  = $product['originalPrice'] ?? ($product['oldPrice'] ?? null);
$inStock   = $product['stock'] > 0;

$categoryName = '';
foreach ($storeData['categories'] as $c) {
    if (($c['id'] ?? '') === ($product['category'] ?? '')) {
        $categoryName = $c['name'] ?? '';
        break;
    }
}

$manufacturerName = $product['manufacturer'] ?? ($product['brand'] ?? 'Sinelec Verified Partner');
$reviewsCount = (int)($product['reviews'] ?? ($product['reviewCount'] ?? 0));
$descriptionText = trim((string)($product['description'] ?? ($product['desc'] ?? '')));
$featuresList = $product['features'] ?? [];

$specRows = [
    'SKU' => $product['sku'] ?? '',
    'Manufacturer' => $manufacturerName,
    'Category' => $categoryName,
    'Package' => $product['package'] ?? '',
    'Operating Voltage' => $product['voltage'] ?? '',
    'Frequency / Speed' => $product['frequency'] ?? '',
    'Minimum Order Qty' => isset($product['minOrder']) ? (string)$product['minOrder'] : '',
    'Stock Available' => isset($product['stock']) ? (string)$product['stock'] : '',
];
$specRows = array_filter($specRows, static fn($val) => $val !== '');

$datasheetLink = (string)($product['datasheet'] ?? '#');
$downloadRows = [
    ['title' => 'User Manual', 'date' => 'Feb 13 2018', 'url' => $datasheetLink],
    ['title' => 'Application Note', 'date' => 'Aug 02 2018', 'url' => 'resources'],
    ['title' => 'Compliance Document', 'date' => 'Jan 21 2022', 'url' => 'request-a-quote'],
    ['title' => 'Product Change Notice', 'date' => 'Nov 10 2022', 'url' => 'resources'],
];
$sampleCode = "/* {$product['sku']} quick start example */\n#include <stdint.h>\n\nint main(void)\n{\n    // Initialize the component interface (I2C/SPI/UART as required)\n    // Configure key registers based on datasheet defaults\n    // Read status / output values and process in your application loop\n\n    while (1) {\n        // Application logic\n    }\n\n    return 0;\n}";

require_once 'header.php';
?>

<main>
<div class="wrap page-wrap">

  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <a href="index">Home</a>
    <span class="bc-sep">›</span>
    <a href="products">Products</a>
    <?php if ($product['category']): ?>
    <span class="bc-sep">›</span>
    <a href="products?cat=<?= urlencode($product['category']) ?>">
      <?php
      foreach ($storeData['categories'] as $c) {
          if ($c['id'] === $product['category']) { echo htmlspecialchars($c['name']); break; }
      }
      ?>
    </a>
    <?php endif; ?>
    <span class="bc-sep">›</span>
    <span><?= htmlspecialchars($product['name']) ?></span>
  </nav>

  <section class="pdpx-shell">
    <div class="pdpx-layout">
      <div class="pdpx-media">
        <div class="pdpx-media-frame">
          <?php if (!empty($product['image'])): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="pdpx-main-img" id="pdpMainImg">
          <?php else: ?>
            <div class="pdp-img-placeholder">
              <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/></svg>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="pdpx-info">
        <?php if (!empty($product['badge'])): ?>
          <span class="pbadge pbadge-<?= htmlspecialchars($product['badge']) ?>"><?= strtoupper(htmlspecialchars($product['badge'])) ?></span>
        <?php endif; ?>

        <h1 class="pdp-title"><?= htmlspecialchars($product['name']) ?></h1>

        <div class="pdpx-meta">
          <span class="pdpx-meta-chip"><?= htmlspecialchars($manufacturerName) ?></span>
          <?php if (!empty($product['sku'])): ?>
            <span class="pdpx-meta-chip">SKU: <?= htmlspecialchars($product['sku']) ?></span>
          <?php endif; ?>
          <?php if ($categoryName !== ''): ?>
            <span class="pdpx-meta-chip"><?= htmlspecialchars($categoryName) ?></span>
          <?php endif; ?>
        </div>

        <div class="pdp-rating-row">
          <div class="star-row" id="pdpStars"></div>
          <span class="pdp-rating-count">(<?= $reviewsCount ?> reviews)</span>
        </div>

        <div class="pdpx-price-wrap">
          <div class="pdp-price">€<?= number_format($basePrice, 2) ?></div>
          <?php if ($oldPrice): ?>
            <div class="pdp-old-price">€<?= number_format($oldPrice, 2) ?></div>
          <?php endif; ?>
          <?php if ($oldPrice && $oldPrice > $basePrice): ?>
            <div class="pdp-save-badge">Save <?= round((($oldPrice - $basePrice) / $oldPrice) * 100) ?>%</div>
          <?php endif; ?>
        </div>

        <div class="pdp-stock-row">
          <?php if ($inStock): ?>
            <span class="in-stock">✓ In Stock (<?= (int)$product['stock'] ?> available)</span>
          <?php else: ?>
            <span class="out-stock">✗ Out of Stock</span>
          <?php endif; ?>
        </div>

        <div class="pdp-actions-row">
          <div class="pdp-qty-wrap">
            <button class="pdp-qty-btn" onclick="pdpQtyChange(-1)">−</button>
            <input type="number" class="pdp-qty-inp" id="pdpQty" value="1" min="1" max="<?= max(1, (int)$product['stock']) ?>">
            <button class="pdp-qty-btn" onclick="pdpQtyChange(1)">+</button>
          </div>
          <button class="btn btn-yellow btn-lg pdp-cart-btn" onclick="pdpAddToCart()" <?= $inStock ? '' : 'disabled' ?>>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
            <?= $inStock ? 'Add to Cart' : 'Out of Stock' ?>
          </button>
          <button class="btn btn-blue btn-lg pdp-buy-btn" onclick="pdpBuyNow()" <?= $inStock ? '' : 'disabled' ?>>
            Buy Now
          </button>
          <button class="pdp-wish-btn" id="pdpWishBtn" onclick="pdpToggleWish()" title="Add to Wishlist">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
          </button>
        </div>

      </div>
    </div>
  </section>

  <section class="pdpx-tabs">
    <div class="pdpx-tabs-nav" role="tablist" aria-label="Product information tabs">
      <button class="pdpx-tab-btn is-active" type="button" data-tab-target="pdpx-desc">Description</button>
      <button class="pdpx-tab-btn" type="button" data-tab-target="pdpx-spec">Specification</button>
      <button class="pdpx-tab-btn" type="button" data-tab-target="pdpx-downloads">Downloads</button>
      <button class="pdpx-tab-btn" type="button" data-tab-target="pdpx-code">Sample Code</button>
    </div>

    <div class="pdpx-tab-panels">
      <div class="pdpx-tab-panel is-active" id="pdpx-desc">
        <p class="pdpx-desc"><?= nl2br(htmlspecialchars($descriptionText !== '' ? $descriptionText : 'Detailed product description will be updated soon.')) ?></p>
        <?php if (!empty($featuresList)): ?>
          <ul class="pdpx-feature-list">
            <?php foreach ($featuresList as $feature): ?>
              <li><?= htmlspecialchars($feature) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div class="pdpx-tab-panel" id="pdpx-spec">
        <table class="pdpx-spec-table">
          <tbody>
          <?php foreach ($specRows as $key => $value): ?>
            <tr>
              <th><?= htmlspecialchars($key) ?></th>
              <td><?= htmlspecialchars($value) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="pdpx-tab-panel" id="pdpx-downloads">
        <div class="pdpx-download-table-wrap">
          <table class="pdpx-download-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Download</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($downloadRows as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['title']) ?></td>
                  <td><?= htmlspecialchars($row['date']) ?></td>
                  <td><a href="<?= htmlspecialchars($row['url']) ?>" class="pdpx-download-link" target="_blank" rel="noopener">Download</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="pdpx-tab-panel" id="pdpx-code">
        <div class="pdpx-code-actions">
          <button type="button" class="pdpx-code-btn" id="copyCodeBtn" aria-label="Copy Code" title="Copy Code">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
              <rect x="9" y="9" width="13" height="13" rx="2"></rect>
              <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
          </button>
          <button type="button" class="pdpx-code-btn pdpx-code-btn--alt" id="downloadCodeBtn" aria-label="Download Code" title="Download Code">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
          </button>
        </div>
        <pre class="pdpx-code-block"><code><?= htmlspecialchars($sampleCode) ?></code></pre>
      </div>
    </div>
  </section>

  <!-- Related Products (JS rendered) -->
  <div class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">Related Products</div>
        <div class="sec-subtitle">Customers also bought</div>
      </div>
    </div>
    <div class="prod-carousel">
      <div class="prod-carousel-track-wrap">
        <div class="prod-carousel-track" id="relatedTrack"></div>
      </div>
    </div>
  </div>

</div>
</main>

<script>
window.CURRENT_PRODUCT = <?= json_encode($product, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
document.addEventListener('DOMContentLoaded', function () {
  const tabButtons = Array.from(document.querySelectorAll('.pdpx-tab-btn'));
  const tabPanels = Array.from(document.querySelectorAll('.pdpx-tab-panel'));
  const copyBtn = document.getElementById('copyCodeBtn');
  const downloadBtn = document.getElementById('downloadCodeBtn');
  const codeNode = document.querySelector('#pdpx-code code');
  const codeText = codeNode ? codeNode.textContent : '';
  const codeFileName = `<?= htmlspecialchars($product['sku'] ?: 'sample-code') ?>.c`;

  function activateTab(targetId) {
    tabButtons.forEach((button) => {
      button.classList.toggle('is-active', button.dataset.tabTarget === targetId);
    });
    tabPanels.forEach((panel) => {
      panel.classList.toggle('is-active', panel.id === targetId);
    });
  }

  tabButtons.forEach((button) => {
    button.addEventListener('click', function () {
      activateTab(button.dataset.tabTarget);
    });
  });

  copyBtn?.addEventListener('click', async function () {
    if (!codeText) return;
    try {
      await navigator.clipboard.writeText(codeText);
      copyBtn.classList.add('is-done');
      setTimeout(() => { copyBtn.classList.remove('is-done'); }, 1000);
    } catch (error) {
      const ta = document.createElement('textarea');
      ta.value = codeText;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      ta.remove();
      copyBtn.classList.add('is-done');
      setTimeout(() => { copyBtn.classList.remove('is-done'); }, 1000);
    }
  });

  downloadBtn?.addEventListener('click', function () {
    if (!codeText) return;
    const blob = new Blob([codeText], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = codeFileName;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
  });
});
</script>

<?php require_once 'footer.php'; ?>
