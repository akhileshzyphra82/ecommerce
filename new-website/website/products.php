<?php
require_once '../data/store_data.php';
$currentPage = 'products';
$pageTitle   = 'Products — Sinelec Tech';

$catFilter = htmlspecialchars($_GET['cat']  ?? '');
$mfrFilter = htmlspecialchars($_GET['mfr']  ?? '');
$query     = htmlspecialchars($_GET['q']    ?? '');
$subcatFilter = htmlspecialchars($_GET['subcat'] ?? '');

if ($catFilter) {
    $catName = '';
    foreach ($storeData['categories'] as $c) {
        if ($c['id'] === $catFilter) { $catName = $c['name']; break; }
    }
    $pageTitle = ($catName ?: $catFilter) . ' — Sinelec Tech';
    if ($subcatFilter) {
        $pageTitle = $subcatFilter . ' — ' . ($catName ?: $catFilter) . ' — Sinelec Tech';
    }
} elseif ($mfrFilter) {
    $pageTitle = $mfrFilter . ' Products — Sinelec Tech';
} elseif ($query) {
    $pageTitle = 'Search: ' . $query . ' — Sinelec Tech';
}

require_once 'header.php';
?>

<main>
<div class="wrap catalog-wrap page-wrap">
  <nav class="breadcrumb" id="catalogBC"></nav>
  <div class="catalog-layout">

    <!-- Filter Sidebar -->
    <aside class="filter-col" id="filterSidebar">
      <div class="filter-header">
        <span>🔍 Filters</span>
        <button onclick="clearFilters()">Clear all</button>
      </div>
      <div class="filter-body">

        <div class="filter-group">
          <div class="filter-group-title">
            Department
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div id="catFilters"></div>
        </div>

        <div class="filter-group">
          <div class="filter-group-title">
            Manufacturer
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div id="mfrFilters"></div>
        </div>

        <div class="filter-group">
          <div class="filter-group-title">
            Price (₹)
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div class="price-row">
            <input type="number" class="price-inp" id="priceMin" placeholder="Min">
            <input type="number" class="price-inp" id="priceMax" placeholder="Max">
          </div>
          <button class="filter-apply-btn" onclick="renderCatalog()">Apply Price Filter</button>
        </div>

        <div class="filter-group">
          <div class="filter-group-title">Availability</div>
          <label class="filter-item">
            <input type="checkbox" class="filter-check" id="filterStock">
            <span>In Stock Only</span>
          </label>
          <label class="filter-item">
            <input type="checkbox" class="filter-check" id="filterNew">
            <span>New Arrivals Only</span>
          </label>
        </div>

      </div>
    </aside>

    <!-- Filter overlay (mobile) -->
    <div class="filter-overlay" id="filterOverlay" onclick="closeFilterPanel()"></div>

    <!-- Main catalog area -->
    <div>
      <div class="catalog-toolbar">
        <span class="results-txt" id="catalogCount">Loading…</span>
        <div class="toolbar-right">
          <button class="btn btn-outline btn-sm" id="mobileFilterBtn" onclick="openFilterPanel()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Filters
          </button>
          <select class="sort-sel" id="sortSel">
            <option value="featured">Sort by: Featured</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="rating">Avg. Customer Review</option>
            <option value="new">Newest Arrivals</option>
            <option value="name">Name A–Z</option>
          </select>
          <div class="view-toggle">
            <button class="vbtn on" data-view="grid" title="Grid view">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </button>
            <button class="vbtn" data-view="list" title="List view">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </button>
          </div>
        </div>
      </div>

      <div id="activeFiltersRow" class="active-filters"></div>
      <div class="prod-grid" id="prodGrid"></div>

      <div class="pagination" id="catalogPagination">
        <button class="pg-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg></button>
        <button class="pg-btn on">1</button>
        <button class="pg-btn">2</button>
        <button class="pg-btn">3</button>
        <button class="pg-btn">…</button>
        <button class="pg-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
      </div>
    </div>

  </div>
</div>

<script>
window.CATALOG_INIT = {
  cat: '<?= $catFilter ?>',
  mfr: '<?= addslashes($mfrFilter) ?>',
  q:   '<?= addslashes($query) ?>',
  subcat: '<?= addslashes($subcatFilter) ?>'
};
</script>
</main>

<?php require_once 'footer.php'; ?>
