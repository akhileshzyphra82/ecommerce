<?php
/**
 * SINELEC TECH — Global Header
 * Expects calling page to set:
 *   $pageTitle   (string) — page <title>
 *   $currentPage (string) — one of: home, products, manufacturers, resources,
 *                           chip-programming, new-arrivals, request-a-quote,
 *                           about, contact, product
 *   $storeData   (array)  — from data/store_data.php
 */

$currentPage = $currentPage ?? 'home';
$pageTitle   ='Sinelec Technologies : Electronic Module and Component Distributor & Expert chip programming services';

function navClass(string $page, string $current): string {
    return $page === $current ? 'nav-link active' : 'nav-link';
}

$productMegaMenu = [
    [
        'id' => 'mcu',
        'name' => 'Microcontrollers',
        'subcategories' => ['ARM Cortex', 'AVR', 'PIC', 'ESP32'],
    ],
    [
        'id' => 'logic',
        'name' => 'Logic ICs',
        'subcategories' => ['Shift Registers', 'Gates', 'Flip-Flops', 'Counters'],
    ],
    [
        'id' => 'opamp',
        'name' => 'Op-Amps & Comparators',
        'subcategories' => ['General Purpose', 'Dual Op-Amp', 'Comparators', 'Low Noise'],
    ],
    [
        'id' => 'power',
        'name' => 'Power Management',
        'subcategories' => ['Linear Regulators', 'LDO Regulators', 'Buck Converters', 'Converters'],
    ],
    [
        'id' => 'transistor',
        'name' => 'Transistors & MOSFETs',
        'subcategories' => ['NPN Transistors', 'Power MOSFETs', 'IGBT', 'Switching'],
    ],
    [
        'id' => 'sensor',
        'name' => 'Sensors & Modules',
        'subcategories' => ['Temperature & Humidity', 'IMU', 'Ultrasonic', 'Motion'],
    ],
    [
        'id' => 'comm',
        'name' => 'Communication ICs',
        'subcategories' => ['RS-232', 'RF 2.4GHz', 'UART/SPI/I2C', 'Wireless Modules'],
    ],
    [
        'id' => 'memory',
        'name' => 'Memory',
        'subcategories' => ['EEPROM', 'Flash', 'SRAM', 'Non-Volatile'],
    ],
    [
        'id' => 'passive',
        'name' => 'Passive Components',
        'subcategories' => ['Resistors', 'Capacitors', 'Inductors', 'Through-Hole Packs'],
    ],
    [
        'id' => 'display',
        'name' => 'Display & LED',
        'subcategories' => ['OLED', 'Character LCD', 'Display Modules', 'LED Drivers'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Sinelec Tech — largest online semiconductor &amp; electronic components store. Genuine ICs, MCUs, sensors, power ICs. Expert chip programming services.">
<meta name="theme-color" content="#131A2E">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
<script>
window.STORE_DATA   = <?= json_encode($storeData ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
window.CURRENT_PAGE = '<?= htmlspecialchars($currentPage) ?>';
</script>
</head>
<body data-page="<?= htmlspecialchars($currentPage) ?>">

<!-- ══════════ HEADER ════════════════════════════════════════ -->
<header class="site-header">
  <div class="wrap">
    <div class="header-main">

      <!-- Mobile Hamburger -->
      <button class="h-hamburger" onclick="openMobMenu()">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <span>Menu</span>
      </button>

      <!-- Logo -->
      <a href="index" class="logo" aria-label="Sinelec Tech — Home">
        <svg width="200" height="52" viewBox="0 0 300 52" xmlns="http://www.w3.org/2000/svg">
          <text x="150" y="28" font-family="Arial, Helvetica, sans-serif" font-size="36" font-weight="800" letter-spacing="3" fill="white" text-anchor="middle" dominant-baseline="middle">SINELEC</text>
          <path d="M30 46 C75 39, 115 39, 150 46 S215 53, 260 46 S300 39, 270 46" fill="none" stroke="#FFCC00" stroke-width="2.5" stroke-linecap="round"/>
        </svg>
      </a>

      <!-- Search -->
      <div class="header-search">
        <form action="products" method="GET" autocomplete="off" class="search-form-contents">
          <select class="search-cat-btn" name="cat" id="searchCat">
            <option value="">All</option>
            <option value="mcu">Microcontrollers</option>
            <option value="logic">Logic ICs</option>
            <option value="opamp">Op-Amps</option>
            <option value="power">Power ICs</option>
            <option value="transistor">Transistors</option>
            <option value="sensor">Sensors</option>
            <option value="comm">Comm ICs</option>
            <option value="memory">Memory</option>
            <option value="display">Display &amp; LED</option>
            <option value="passive">Passives</option>
          </select>
          <input class="search-field" id="searchField" type="text" name="q"
                 placeholder="Search part number, description or manufacturer…"
                 oninput="onSearchInput(event)"
                 value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
          <button class="search-go" type="submit" aria-label="Search">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </button>
        </form>
        <div class="search-drop" id="searchDrop"></div>
      </div>

      <!-- Delivery Location -->
      <div class="header-delivery" title="Change delivery location">
        <span class="h-label">Deliver to</span>
        <strong class="h-value">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          Delhi 110001
        </strong>
      </div>

      <!-- Account -->
      <div class="h-act" title="Account">
        <span class="h-label">Hello, Sign in</span>
        <strong class="h-value">
          Account &amp; Lists
          <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </strong>
      </div>

      <!-- Cart -->
      <button class="h-cart" onclick="openCart()" aria-label="Shopping cart">
        <span class="h-cart-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          <span class="cart-count hidden">0</span>
        </span>
        <span class="h-cart-label">Cart</span>
      </button>

    </div>
  </div>

  <!-- Nav Bar -->
  <nav class="nav-bar" aria-label="Main navigation">
    <div class="wrap">
      <div class="nav-inner">

        <!-- Home -->
        <a href="index" class="<?= navClass('home', $currentPage) ?> nav-deals">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          Home
        </a>

        <!-- Products mega menu -->
        <div class="nav-item" id="productsNavItem">
          <a href="products" class="nav-link nav-link-drop <?= in_array($currentPage, ['products', 'new-arrivals']) ? 'active' : '' ?>"
             onclick="toggleProductsMenu(event)">
            Products
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </a>
          <div class="mega-menu mega-products">

            <!-- LEFT: Category list -->
            <div class="mega-cats-col">
              <a href="new-arrivals" class="mega-cat mega-cat-new active" data-cat-id="newest">
                Newest Products
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
              <div class="mega-cats-divider"></div>
              <?php foreach ($productMegaMenu as $menuCategory): ?>
              <a
                href="products?cat=<?= urlencode($menuCategory['id']) ?>"
                class="mega-cat"
                data-cat-id="<?= htmlspecialchars($menuCategory['id']) ?>"
              >
                <?= htmlspecialchars($menuCategory['name']) ?>
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
              <?php endforeach; ?>
              <a href="products" class="mega-see-all">SEE ALL</a>
            </div>

            <!-- RIGHT: Dynamic content panels -->
            <div class="mega-content-col">

              <div class="mega-panel active" data-panel-id="newest">
                <div class="mega-panel-head">
                  <span>Shop Newest Products <strong>By Category</strong></span>
                  <a href="new-arrivals" class="mega-panel-viewall">View All Newest →</a>
                </div>
                <div class="mega-cat-imgrid">
                  <?php foreach (array_slice($storeData['categories'] ?? [], 0, 6) as $idx => $categoryCard): ?>
                  <a href="products?cat=<?= urlencode($categoryCard['id']) ?>" class="mega-cat-card">
                    <div class="mega-cat-card-img <?= $idx % 3 === 0 ? 'mega-cat-card-img--blue' : ($idx % 3 === 1 ? 'mega-cat-card-img--green' : 'mega-cat-card-img--orange') ?>">
                      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <rect x="8" y="8" width="8" height="8" rx="1.5"/>
                      </svg>
                    </div>
                    <div class="mega-cat-card-name"><?= htmlspecialchars($categoryCard['name']) ?></div>
                  </a>
                  <?php endforeach; ?>
                </div>
              </div>

              <?php foreach ($productMegaMenu as $menuCategory): ?>
              <div class="mega-panel" data-panel-id="<?= htmlspecialchars($menuCategory['id']) ?>">
                <div class="mega-panel-head">
                  <strong>Types of <?= htmlspecialchars($menuCategory['name']) ?></strong>
                  <a href="products?cat=<?= urlencode($menuCategory['id']) ?>" class="mega-panel-viewall">View all <?= htmlspecialchars($menuCategory['name']) ?> →</a>
                </div>
                <div class="mega-sub-2col">
                  <?php foreach ($menuCategory['subcategories'] as $subCategory): ?>
                  <a href="products?cat=<?= urlencode($menuCategory['id']) ?>&subcat=<?= urlencode($subCategory) ?>" class="mega-sub-link"><?= htmlspecialchars($subCategory) ?></a>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endforeach; ?>

            </div><!-- end mega-content-col -->
          </div>
        </div>

        <!-- Manufacturers -->
        <div class="nav-item">
          <a href="manufacturers" class="nav-link nav-link-drop <?= navClass('manufacturers', $currentPage) === 'nav-link active' ? 'active' : '' ?>">
            Manufacturers
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </a>
          <div class="mega-menu mega-simple">
            <div class="mega-simple-title">Our Manufacturers</div>
            <?php foreach ($storeData['manufacturers'] as $mfr): ?>
            <a href="products?mfr=<?= urlencode($mfr['name']) ?>" class="mega-simple-link"><?= htmlspecialchars($mfr['name']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Resources -->
        <div class="nav-item">
          <a href="resources" class="nav-link nav-link-drop <?= $currentPage === 'resources' ? 'active' : '' ?>">
            Resources
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </a>
          <div class="mega-menu mega-simple">
            <div class="mega-simple-title">Resources</div>
            <a href="resources#learning" class="mega-simple-link">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
              Learning Material
            </a>
            <a href="resources#datasheets" class="mega-simple-link">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              Datasheets
            </a>
            <a href="resources#manuals" class="mega-simple-link">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/></svg>
              Manuals
            </a>
            <a href="resources#appnotes" class="mega-simple-link">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Application Notes
            </a>
          </div>
        </div>

        <!-- Chip Programming -->
        <a href="chip-programming" class="<?= navClass('chip-programming', $currentPage) ?>">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M17 7l5 5-5 5M7 7l-5 5 5 5M14 3l-4 18"/></svg>
          Chip Programming
          <span class="nav-badge">New</span>
        </a>

        <!-- New Arrivals -->
        <a href="new-arrivals" class="<?= navClass('new-arrivals', $currentPage) ?>">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          New
        </a>

        <!-- Request a Quote -->
        <a href="request-a-quote" class="<?= navClass('request-a-quote', $currentPage) ?>">Request a Quote</a>

        <!-- About Us -->
        <a href="about" class="<?= navClass('about', $currentPage) ?>">About Us</a>

      </div>
    </div>
  </nav>
</header>

<!-- ══════════ MOBILE MENU ════════════════════════════════════ -->
<div class="mobile-menu" id="mobMenu" aria-hidden="true">
  <div class="mob-overlay" onclick="closeMobMenu()"></div>
  <div class="mob-panel">
    <div class="mob-hd">
      <div class="mob-hd-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Hello, Guest
      </div>
      <button class="mob-close" onclick="closeMobMenu()" aria-label="Close menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <nav class="mob-nav">
      <a href="index" class="mob-link <?= $currentPage === 'home' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Home
      </a>
      <a href="products" class="mob-link <?= $currentPage === 'products' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        All Products
      </a>
      <div class="mob-divider"></div>
      <div class="mob-section-title">Shop by Category</div>
      <div class="mob-cat-grid">
        <a href="products?cat=mcu"        class="mob-cat-btn">Microcontrollers</a>
        <a href="products?cat=sensor"     class="mob-cat-btn">Sensors</a>
        <a href="products?cat=power"      class="mob-cat-btn">Power ICs</a>
        <a href="products?cat=logic"      class="mob-cat-btn">Logic ICs</a>
        <a href="products?cat=transistor" class="mob-cat-btn">Transistors</a>
        <a href="products?cat=comm"       class="mob-cat-btn">Comm ICs</a>
        <a href="products?cat=memory"     class="mob-cat-btn">Memory</a>
        <a href="products?cat=passive"    class="mob-cat-btn">Passives</a>
        <a href="products?cat=display"    class="mob-cat-btn">Display</a>
        <a href="products?cat=opamp"      class="mob-cat-btn">Op-Amps</a>
      </div>
      <div class="mob-divider"></div>
      <a href="chip-programming" class="mob-link <?= $currentPage === 'chip-programming' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M17 7l5 5-5 5M7 7l-5 5 5 5M14 3l-4 18"/></svg>
        Chip Programming Service
      </a>
      <a href="new-arrivals" class="mob-link <?= $currentPage === 'new-arrivals' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        New Arrivals
      </a>
      <a href="manufacturers" class="mob-link <?= $currentPage === 'manufacturers' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
        Manufacturers
      </a>
      <a href="about" class="mob-link <?= $currentPage === 'about' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/></svg>
        About Us
      </a>
      <a href="about#contact" class="mob-link <?= $currentPage === 'contact' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07"/></svg>
        Contact / Bulk Orders
      </a>
    </nav>
    <div class="mob-footer-contact">
      <a href="tel:+919876543210" class="mob-footer-phone">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 8.81"/></svg>
        +91-9876543210
      </a>
    </div>
  </div>
</div>
