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

$currentPage = 'products';
$pageTitle   = htmlspecialchars($product['name']) . ' — Sinelec Tech';

$basePrice = $product['priceBreaks'][0]['price'] ?? 0;
$oldPrice  = $product['oldPrice'] ?? null;
$inStock   = $product['stock'] > 0;

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

  <!-- PDP Layout -->
  <div class="pdp-layout">

    <!-- Images -->
    <div class="pdp-gallery">
      <div class="pdp-main-img-wrap">
        <?php if ($product['image']): ?>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="pdp-main-img" id="pdpMainImg">
        <?php else: ?>
        <div class="pdp-img-placeholder">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/></svg>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Details -->
    <div class="pdp-info">
      <?php if ($product['badge']): ?>
      <span class="pbadge pbadge-<?= htmlspecialchars($product['badge']) ?>"><?= strtoupper(htmlspecialchars($product['badge'])) ?></span>
      <?php endif; ?>

      <h1 class="pdp-title"><?= htmlspecialchars($product['name']) ?></h1>

      <div class="pdp-meta-row">
        <span class="pdp-brand"><?= htmlspecialchars($product['brand']) ?></span>
        <?php if ($product['partNo']): ?>
        <span class="pdp-partno">PN: <?= htmlspecialchars($product['partNo']) ?></span>
        <?php endif; ?>
      </div>

      <!-- Rating -->
      <div class="pdp-rating-row">
        <div class="star-row" id="pdpStars"></div>
        <span class="pdp-rating-count">(<?= (int)$product['reviewCount'] ?> reviews)</span>
      </div>

      <!-- Price -->
      <div class="pdp-price-block">
        <div class="pdp-price">₹<?= number_format($basePrice, 2) ?></div>
        <?php if ($oldPrice): ?>
        <div class="pdp-old-price">₹<?= number_format($oldPrice, 2) ?></div>
        <?php endif; ?>
        <?php if ($oldPrice && $oldPrice > $basePrice): ?>
        <div class="pdp-save-badge">Save <?= round((($oldPrice - $basePrice) / $oldPrice) * 100) ?>%</div>
        <?php endif; ?>
      </div>

      <!-- Price Breaks -->
      <?php if (count($product['priceBreaks']) > 1): ?>
      <div class="pdp-price-breaks">
        <div class="pdp-pb-title">Volume Pricing</div>
        <table class="pdp-pb-table">
          <thead><tr><th>Qty</th><th>Unit Price</th><th>Savings</th></tr></thead>
          <tbody>
          <?php foreach ($product['priceBreaks'] as $pb): ?>
          <tr>
            <td><?= (int)$pb['qty'] ?>+</td>
            <td>₹<?= number_format($pb['price'], 2) ?></td>
            <td><?php if ($pb['price'] < $basePrice): ?>
              <span class="pdp-pb-save">-<?= round((($basePrice - $pb['price']) / $basePrice) * 100) ?>%</span>
            <?php else: echo '—'; endif; ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>

      <!-- Stock -->
      <div class="pdp-stock-row">
        <?php if ($inStock): ?>
        <span class="in-stock">✓ In Stock (<?= (int)$product['stock'] ?> available)</span>
        <?php else: ?>
        <span class="out-stock">✗ Out of Stock</span>
        <?php endif; ?>
      </div>

      <!-- Add to Cart -->
      <div class="pdp-actions-row">
        <div class="pdp-qty-wrap">
          <button class="pdp-qty-btn" onclick="pdpQtyChange(-1)">−</button>
          <input type="number" class="pdp-qty-inp" id="pdpQty" value="1" min="1" max="<?= (int)$product['stock'] ?>">
          <button class="pdp-qty-btn" onclick="pdpQtyChange(1)">+</button>
        </div>
        <button class="btn btn-yellow btn-lg pdp-cart-btn" onclick="pdpAddToCart()" <?= $inStock ? '' : 'disabled' ?>>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          <?= $inStock ? 'Add to Cart' : 'Out of Stock' ?>
        </button>
        <button class="pdp-wish-btn" id="pdpWishBtn" onclick="pdpToggleWish()" title="Add to Wishlist">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        </button>
      </div>

      <!-- Description -->
      <?php if ($product['desc']): ?>
      <div class="pdp-desc-box">
        <div class="pdp-desc-text"><?= nl2br(htmlspecialchars($product['desc'])) ?></div>
      </div>
      <?php endif; ?>

      <!-- Specs -->
      <?php if (!empty($product['specs'])): ?>
      <div class="pdp-specs-box">
        <div class="pdp-specs-title">Specifications</div>
        <table class="pdp-specs-table">
          <?php foreach ($product['specs'] as $key => $val): ?>
          <tr>
            <td class="pdp-spec-key"><?= htmlspecialchars($key) ?></td>
            <td class="pdp-spec-val"><?= htmlspecialchars($val) ?></td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>
      <?php endif; ?>

    </div><!-- /pdp-info -->
  </div><!-- /pdp-layout -->

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
</script>

<?php require_once 'footer.php'; ?>
