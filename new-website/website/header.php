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
      <div class="header-delivery" id="headerDeliveryBtn" title="Change delivery location" role="button" tabindex="0" aria-haspopup="dialog" aria-controls="deliveryModal">
        <span class="h-label">Deliver to</span>
        <strong class="h-value">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span id="deliveryLocationText" class="delivery-loc-text">Delhi 110001</span>
        </strong>
      </div>

      <!-- Account -->
      <div class="h-act" id="headerAccountBtn" title="Account" role="button" tabindex="0" aria-haspopup="dialog" aria-controls="authModal">
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

        <!-- Request a Quote -->
        <a href="request-a-quote" class="<?= navClass('request-a-quote', $currentPage) ?>">Request a Quote</a>

        <!-- About Sinelec -->
        <a href="about" class="<?= navClass('about', $currentPage) ?>">About Sinelec</a>

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
      <a href="manufacturers" class="mob-link <?= $currentPage === 'manufacturers' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
        Manufacturers
      </a>
      <a href="about" class="mob-link <?= $currentPage === 'about' ? 'on' : '' ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/></svg>
        About Sinelec
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

<!-- ══════════ DELIVERY LOCATION MODAL ═══════════════════════ -->
<div class="delivery-modal" id="deliveryModal" hidden>
  <div class="delivery-modal-backdrop" data-delivery-close></div>
  <div class="delivery-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="deliveryModalTitle">
    <button type="button" class="delivery-modal-close" data-delivery-close aria-label="Close">×</button>
    <h3 class="delivery-modal-title" id="deliveryModalTitle">Choose Delivery Location</h3>
    <p class="delivery-modal-subtitle">Set where you want orders to be delivered for accurate stock and shipping estimates.</p>

    <div class="delivery-modal-block">
      <div class="delivery-modal-label">Choose your current location</div>
      <button type="button" class="delivery-modal-btn" id="useCurrentLocBtn">Use Current Location</button>
    </div>

    <div class="delivery-modal-or">Or</div>

    <div class="delivery-modal-block">
      <div class="delivery-modal-label">Enter your location manually</div>
      <div class="delivery-manual-row">
        <input type="text" id="manualLocationInput" class="delivery-manual-inp" placeholder="City / Area / PIN Code">
        <button type="button" class="delivery-modal-btn delivery-modal-btn--apply" id="applyManualLocBtn">Set</button>
      </div>
    </div>

    <div class="delivery-modal-or">Or</div>

    <div class="delivery-modal-block">
      <div class="delivery-modal-label">Select existing address</div>
      <div class="delivery-address-list" id="deliveryAddressList">
        <label class="delivery-address-item">
          <input type="radio" name="deliveryAddress" value="Suite 3, Floor 8, Bldg. 3, Mindspace SEZ, Airoli, Navi Mumbai, Maharashtra 400708">
          <span class="delivery-address-main">Suite 3, Floor 8, Bldg. 3, Mindspace SEZ, Airoli, Navi Mumbai, Maharashtra 400708</span>
          <span class="delivery-address-actions">
            <button type="button" class="delivery-address-action" data-address-action="edit" aria-label="Edit address" title="Edit address">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
            </button>
            <button type="button" class="delivery-address-action delivery-address-action--del" data-address-action="delete" aria-label="Delete address" title="Delete address">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </span>
        </label>
        <label class="delivery-address-item">
          <input type="radio" name="deliveryAddress" value="Office 12B, 4th Floor, Plot 9, Connaught Place, New Delhi 110001">
          <span class="delivery-address-main">Office 12B, 4th Floor, Plot 9, Connaught Place, New Delhi 110001</span>
          <span class="delivery-address-actions">
            <button type="button" class="delivery-address-action" data-address-action="edit" aria-label="Edit address" title="Edit address">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
            </button>
            <button type="button" class="delivery-address-action delivery-address-action--del" data-address-action="delete" aria-label="Delete address" title="Delete address">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </span>
        </label>
        <label class="delivery-address-item">
          <input type="radio" name="deliveryAddress" value="Brachvogelweg 9, 85375 Neufahrn, Germany">
          <span class="delivery-address-main">Brachvogelweg 9, 85375 Neufahrn, Germany</span>
          <span class="delivery-address-actions">
            <button type="button" class="delivery-address-action" data-address-action="edit" aria-label="Edit address" title="Edit address">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
            </button>
            <button type="button" class="delivery-address-action delivery-address-action--del" data-address-action="delete" aria-label="Delete address" title="Delete address">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </span>
        </label>
      </div>
    </div>
  </div>
</div>

<!-- ══════════ AUTH MODAL ═════════════════════════════════════ -->
<div class="auth-modal" id="authModal" hidden>
  <div class="auth-modal-backdrop" data-auth-close></div>
  <div class="auth-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
    <button type="button" class="auth-modal-close" data-auth-close aria-label="Close">×</button>

    <h3 class="auth-title" id="authModalTitle">Sign In</h3>
    <p class="auth-subtitle" id="authModalDesc">Sign in to continue.</p>

    <div class="auth-panel auth-panel-signin is-active" id="authSignInPanel">
      <form id="authSignInForm" class="auth-form" novalidate>
        <label class="auth-field">
          <span>User ID</span>
          <div class="auth-input-wrap">
            <span class="auth-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z" opacity=".0"/><path d="M4 6l8 6 8-6"/><rect x="3" y="5" width="18" height="14" rx="2"/></svg>
            </span>
            <input type="text" id="authUserId" required>
          </div>
        </label>

        <label class="auth-field">
          <span>Password</span>
          <div class="auth-input-wrap">
            <span class="auth-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 10 0v3"/></svg>
            </span>
            <input type="password" id="authPassword" required>
            <button type="button" class="auth-pass-eye" data-toggle-pass data-pass-target="authPassword" aria-label="Show password" title="Show password">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </label>

        <div class="auth-captcha auth-captcha-cloud">
          <div class="auth-captcha-left">
            <span class="auth-captcha-ok">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span>Success!</span>
          </div>
          <div class="auth-captcha-brand">
            <strong>CLOUDFLARE</strong>
            <span>Privacy · Help</span>
          </div>
        </div>

        <button type="submit" class="auth-primary-btn">Sign In</button>
      </form>

      <div class="auth-sep"><span>or</span></div>
      <button type="button" class="auth-google-btn">
        <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
          <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.656 32.657 29.205 36 24 36c-6.627 0-12-5.373-12-12S17.373 12 24 12c3.06 0 5.849 1.154 7.971 3.029l5.657-5.657C34.053 6.053 29.277 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
          <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 16.108 19.013 12 24 12c3.06 0 5.849 1.154 7.971 3.029l5.657-5.657C34.053 6.053 29.277 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
          <path fill="#4CAF50" d="M24 44c5.176 0 9.86-1.977 13.409-5.197l-6.19-5.238C29.141 35.091 26.715 36 24 36c-5.184 0-9.623-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
          <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.094 5.565l.003-.002 6.19 5.238C37.005 39.163 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
        </svg>
        Continue with Google
      </button>

      <div class="auth-links-row">
        <button type="button" class="auth-link-btn" data-auth-switch="signup">Create new account</button>
        <button type="button" class="auth-link-btn" id="authForgotBtn">Forgot password</button>
      </div>

      <p class="auth-terms">By continuing, you agree to our <a href="#">Terms of Use</a> &amp; <a href="#">Privacy Policy</a>.</p>
    </div>

    <div class="auth-panel auth-panel-signup" id="authSignUpPanel">
      <form id="authSignUpForm" class="auth-form" novalidate>
        <div class="auth-two-col">
          <label class="auth-field">
            <span>First Name</span>
            <input type="text" id="authFirstName" required>
          </label>
          <label class="auth-field">
            <span>Last Name</span>
            <input type="text" id="authLastName" required>
          </label>
        </div>

        <label class="auth-field">
          <span>Email ID</span>
          <div class="auth-input-wrap">
            <span class="auth-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6l8 6 8-6"/><rect x="3" y="5" width="18" height="14" rx="2"/></svg>
            </span>
            <input type="email" id="authEmail" required>
          </div>
        </label>

        <label class="auth-field">
          <span>Number</span>
          <div class="auth-input-wrap">
            <span class="auth-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1.8.3 1.5.6 2.3a2 2 0 0 1-.5 2.1L8 9a16 16 0 0 0 7 7l.9-.9a2 2 0 0 1 2.1-.5c.8.3 1.5.5 2.3.6A2 2 0 0 1 22 16.9z"/></svg>
            </span>
            <input type="tel" id="authPhone" required>
          </div>
        </label>

        <label class="auth-field">
          <span>Password</span>
          <div class="auth-input-wrap">
            <span class="auth-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 10 0v3"/></svg>
            </span>
            <input type="password" id="authPassCreate" required>
            <button type="button" class="auth-pass-eye" data-toggle-pass data-pass-target="authPassCreate" aria-label="Show password" title="Show password">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </label>

        <label class="auth-field">
          <span>Confirm Password</span>
          <div class="auth-input-wrap">
            <span class="auth-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 10 0v3"/></svg>
            </span>
            <input type="password" id="authPassConfirm" required>
            <button type="button" class="auth-pass-eye" data-toggle-pass data-pass-target="authPassConfirm" aria-label="Show password" title="Show password">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </label>

        <div class="auth-captcha auth-captcha-cloud">
          <div class="auth-captcha-left">
            <span class="auth-captcha-ok">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span>Success!</span>
          </div>
          <div class="auth-captcha-brand">
            <strong>CLOUDFLARE</strong>
            <span>Privacy · Help</span>
          </div>
        </div>

        <button type="submit" class="auth-primary-btn">Create Account</button>
      </form>

      <div class="auth-sep"><span>or</span></div>
      <button type="button" class="auth-google-btn">
        <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
          <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.656 32.657 29.205 36 24 36c-6.627 0-12-5.373-12-12S17.373 12 24 12c3.06 0 5.849 1.154 7.971 3.029l5.657-5.657C34.053 6.053 29.277 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
          <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 16.108 19.013 12 24 12c3.06 0 5.849 1.154 7.971 3.029l5.657-5.657C34.053 6.053 29.277 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
          <path fill="#4CAF50" d="M24 44c5.176 0 9.86-1.977 13.409-5.197l-6.19-5.238C29.141 35.091 26.715 36 24 36c-5.184 0-9.623-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
          <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.094 5.565l.003-.002 6.19 5.238C37.005 39.163 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
        </svg>
        Continue with Google
      </button>

      <div class="auth-links-row auth-links-row-center">
        <span>Already have account?</span>
        <button type="button" class="auth-link-btn" data-auth-switch="signin">Sign in</button>
      </div>

      <p class="auth-terms">By continuing, you agree to our <a href="#">Terms of Use</a> &amp; <a href="#">Privacy Policy</a>.</p>
    </div>
  </div>
</div>
