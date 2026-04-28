<?php
require_once '../data/store_data.php';
$currentPage = 'chip-programming';
$pageTitle   = 'Chip Programming Services — Sinelec Tech';
require_once 'header.php';
?>

<main>
<div class="wrap page-wrap">

  <!-- Hero -->
  <div class="chip-hero">
    <div class="chip-hero-eyebrow">Expert Engineering Services</div>
    <h1 class="chip-hero-title">Semiconductor Programming &amp; Engineering Services</h1>
    <p class="chip-hero-sub">From single chip programming to full product development — we're your engineering partner. Fast 48-hour turnaround.</p>
    <div class="chip-hero-ctas">
      <a href="request-a-quote" class="btn btn-yellow btn-lg">Get a Quote</a>
      <a href="products?cat=mcu" class="btn btn-ghost-white btn-lg">Browse MCUs →</a>
    </div>
  </div>

  <!-- Services Grid (rendered by JS from STORE_DATA.services) -->
  <div class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">Our Services</div>
        <div class="sec-subtitle">Professional electronics engineering services for every project</div>
      </div>
    </div>
    <div class="srv-grid" id="srvGrid"></div>
  </div>

  <!-- How It Works -->
  <div class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">How Chip Programming Works</div>
        <div class="sec-subtitle">Simple, fast, and fully managed — from order to programmed chip</div>
      </div>
    </div>
    <div class="how-grid">
      <div class="how-step">
        <div class="how-step-num">1</div>
        <h4 class="how-step-title">Order Online</h4>
        <p class="how-step-desc">Select your MCU from our catalog and add the chip programming service at checkout</p>
      </div>
      <div class="how-step">
        <div class="how-step-num">2</div>
        <h4 class="how-step-title">Share Firmware</h4>
        <p class="how-step-desc">Email or WhatsApp us your firmware .hex / .bin file and programming specifications</p>
      </div>
      <div class="how-step">
        <div class="how-step-num">3</div>
        <h4 class="how-step-title">We Program</h4>
        <p class="how-step-desc">Our engineers flash your firmware and perform functional testing on every unit</p>
      </div>
      <div class="how-step">
        <div class="how-step-num">4</div>
        <h4 class="how-step-title">Fast Delivery</h4>
        <p class="how-step-desc">Programmed and tested chips dispatched within 24–48 hours of receiving your firmware</p>
      </div>
    </div>
    <div class="how-cta-row">
      <a href="request-a-quote" class="btn btn-orange btn-lg">Get a Quote for Your Project</a>
      <a href="products?cat=mcu" class="btn btn-outline btn-lg">Browse MCUs</a>
    </div>
  </div>

  <!-- Supported Platforms -->
  <div class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">Supported Platforms</div>
        <div class="sec-subtitle">We program all major MCU families</div>
      </div>
    </div>
    <div class="mfr-grid">
      <?php
      $platforms = [
        ['name' => 'Arduino / AVR',  'note' => 'Uno, Nano, Mega, Pro Mini'],
        ['name' => 'STM32',          'note' => 'F0 / F1 / F4 / G0 / H7 series'],
        ['name' => 'ESP32 / ESP8266','note' => 'Wi-Fi + BT, custom firmware'],
        ['name' => 'PIC',            'note' => 'PIC16 / PIC18 / PIC32'],
        ['name' => 'nRF52 / nRF5340','note' => 'Bluetooth 5, Zigbee, Thread'],
        ['name' => 'RP2040',         'note' => 'Raspberry Pi Pico platform'],
        ['name' => 'MSP430',         'note' => 'Ultra-low-power TI MCUs'],
        ['name' => 'RISC-V',         'note' => 'GD32, CH32, custom cores'],
      ];
      foreach ($platforms as $p):
      ?>
      <div class="mfr-card">
        <div class="mfr-logo-placeholder"><?= htmlspecialchars(substr($p['name'], 0, 2)) ?></div>
        <div class="mfr-card-name"><?= htmlspecialchars($p['name']) ?></div>
        <div class="mfr-card-country"><?= htmlspecialchars($p['note']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Pricing -->
  <div class="services-strip">
    <div class="services-strip-hd">
      <div>
        <div class="services-strip-eyebrow">Transparent Pricing</div>
        <h2 class="services-strip-title">Simple, Flat-Rate Programming Fees</h2>
        <p class="services-strip-sub">No hidden charges. Volume discounts available for 10+ units.</p>
      </div>
      <a href="request-a-quote" class="btn btn-yellow btn-lg flex-shrink-0">Get Custom Quote</a>
    </div>
    <div class="services-strip-cards">
      <div class="srv-strip-card">
        <div class="srv-strip-card-icon">⚡</div>
        <div class="srv-strip-card-title">Standard (1–9 chips)</div>
        <div class="srv-strip-card-desc">Single or small batch programming with full test report</div>
        <div class="srv-strip-card-price">From ₹499/chip</div>
      </div>
      <div class="srv-strip-card">
        <div class="srv-strip-card-icon">📦</div>
        <div class="srv-strip-card-title">Volume (10–99 chips)</div>
        <div class="srv-strip-card-desc">Discounted batch programming — ideal for prototypes and small runs</div>
        <div class="srv-strip-card-price">From ₹299/chip</div>
      </div>
      <div class="srv-strip-card">
        <div class="srv-strip-card-icon">🏭</div>
        <div class="srv-strip-card-title">Production (100+ chips)</div>
        <div class="srv-strip-card-desc">High-volume production programming with QC certificate</div>
        <div class="srv-strip-card-price">Custom Quote</div>
      </div>
      <div class="srv-strip-card">
        <div class="srv-strip-card-icon">🔧</div>
        <div class="srv-strip-card-title">Bring Your Own Chip</div>
        <div class="srv-strip-card-desc">Ship us your chips — we program and return within 48 hours</div>
        <div class="srv-strip-card-price">From ₹149/chip</div>
      </div>
    </div>
  </div>

</div>
</main>

<?php require_once 'footer.php'; ?>
