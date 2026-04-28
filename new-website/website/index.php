<?php
require_once '../data/store_data.php';
$currentPage = 'home';
$pageTitle   = 'Sinelec Tech — India\'s #1 Online Semiconductor Store';
require_once 'header.php';
?>

<main>

<!-- ═══════════════════════════════════════════════════════════
     HERO CAROUSEL
═══════════════════════════════════════════════════════════ -->
<div class="hero">
  <div id="heroTrack" class="hero-track">

    <!-- Slide 1 -->
    <div class="hero-slide hero-slide-1">
      <div class="wrap hero-slide-wrap">
        <div class="hero-content">
          <div class="hero-eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            250,000+ Genuine Components
          </div>
          <h1 class="hero-title">India's #1 Online <span class="hl">Semiconductor</span> Store</h1>
          <p class="hero-subtitle">Genuine ICs · Microcontrollers · Sensors · Power Modules<br>Expert Chip Programming · Pan-India Fast Delivery</p>
          <div class="hero-ctas">
            <a href="products" class="btn btn-yellow btn-lg">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              Shop Now
            </a>
            <a href="chip-programming" class="btn btn-ghost-white btn-lg">Chip Programming →</a>
          </div>
        </div>
      </div>
      <div class="hero-visual">
        <svg class="hero-chip-graphic" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="80" y="80" width="240" height="240" rx="20" stroke="white" stroke-width="4"/>
          <rect x="120" y="120" width="160" height="160" rx="10" stroke="white" stroke-width="2.5"/>
          <rect x="150" y="150" width="100" height="100" rx="5" fill="white" opacity="0.15"/>
          <line x1="80" y1="160" x2="0" y2="160" stroke="white" stroke-width="2.5"/><circle cx="0" cy="160" r="6" fill="white"/>
          <line x1="80" y1="200" x2="0" y2="200" stroke="white" stroke-width="2.5"/><circle cx="0" cy="200" r="6" fill="white"/>
          <line x1="80" y1="240" x2="0" y2="240" stroke="white" stroke-width="2.5"/><circle cx="0" cy="240" r="6" fill="white"/>
          <line x1="320" y1="160" x2="400" y2="160" stroke="white" stroke-width="2.5"/><circle cx="400" cy="160" r="6" fill="white"/>
          <line x1="320" y1="200" x2="400" y2="200" stroke="white" stroke-width="2.5"/><circle cx="400" cy="200" r="6" fill="white"/>
          <line x1="320" y1="240" x2="400" y2="240" stroke="white" stroke-width="2.5"/><circle cx="400" cy="240" r="6" fill="white"/>
          <line x1="160" y1="80" x2="160" y2="0" stroke="white" stroke-width="2.5"/><circle cx="160" cy="0" r="6" fill="white"/>
          <line x1="200" y1="80" x2="200" y2="0" stroke="white" stroke-width="2.5"/><circle cx="200" cy="0" r="6" fill="white"/>
          <line x1="240" y1="80" x2="240" y2="0" stroke="white" stroke-width="2.5"/><circle cx="240" cy="0" r="6" fill="white"/>
          <line x1="160" y1="320" x2="160" y2="400" stroke="white" stroke-width="2.5"/><circle cx="160" cy="400" r="6" fill="white"/>
          <line x1="200" y1="320" x2="200" y2="400" stroke="white" stroke-width="2.5"/><circle cx="200" cy="400" r="6" fill="white"/>
          <line x1="240" y1="320" x2="240" y2="400" stroke="white" stroke-width="2.5"/><circle cx="240" cy="400" r="6" fill="white"/>
          <text x="175" y="210" fill="white" font-size="22" font-weight="bold" font-family="monospace">MCU</text>
        </svg>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="hero-slide hero-slide-2">
      <div class="wrap hero-slide-wrap">
        <div class="hero-content">
          <div class="hero-eyebrow">Starting ₹499 / chip</div>
          <h1 class="hero-title">Custom <span class="hl">Chip Programming</span> Service</h1>
          <p class="hero-subtitle">Flash custom firmware to any MCU — Arduino, STM32, PIC, AVR, ESP32. Send us your chip or we supply it. Fast 48-hour turnaround.</p>
          <div class="hero-ctas">
            <a href="chip-programming" class="btn btn-yellow btn-lg">Get Started</a>
            <a href="request-a-quote" class="btn btn-ghost-white btn-lg">Get a Quote</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="hero-slide hero-slide-3">
      <div class="wrap hero-slide-wrap">
        <div class="hero-content">
          <div class="hero-eyebrow">🎉 Limited Time Offer</div>
          <h1 class="hero-title">Upto <span class="hl">40% Off</span> on Bulk Orders</h1>
          <p class="hero-subtitle">Volume pricing on 100+ units. Competitive rates for your production runs. Genuine parts from authorised distributors. Nationwide delivery.</p>
          <div class="hero-ctas">
            <a href="request-a-quote" class="btn btn-yellow btn-lg">Request Quote</a>
            <a href="products" class="btn btn-ghost-white btn-lg">Browse Products</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 4 -->
    <div class="hero-slide hero-slide-4">
      <div class="wrap hero-slide-wrap">
        <div class="hero-content">
          <div class="hero-eyebrow">⚡ New Arrivals</div>
          <h1 class="hero-title">Latest <span class="hl">STM32G0</span> &amp; ESP32-S3 Now In Stock</h1>
          <p class="hero-subtitle">Shop the newest microcontrollers and development modules. STM32G0B1, ESP32-S3-WROOM, nRF5340 and more.</p>
          <div class="hero-ctas">
            <a href="new-arrivals" class="btn btn-yellow btn-lg">Shop New Arrivals</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 5 -->
    <div class="hero-slide hero-slide-5">
      <div class="wrap hero-slide-wrap">
        <div class="hero-content">
          <div class="hero-eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/></svg>
            ARM · AVR · PIC · ESP32
          </div>
          <h1 class="hero-title">250,000+ Genuine <span class="hl">IC Components</span></h1>
          <p class="hero-subtitle">From basic logic gates to advanced ARM Cortex-M7 MCUs — every part sourced from authorised distributors and verified for authenticity.</p>
          <div class="hero-ctas">
            <a href="products?cat=mcu" class="btn btn-yellow btn-lg">Browse Microcontrollers</a>
            <a href="products" class="btn btn-ghost-white btn-lg">All Categories</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 6 -->
    <div class="hero-slide hero-slide-6">
      <div class="wrap hero-slide-wrap">
        <div class="hero-content">
          <div class="hero-eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Expert Engineering Services
          </div>
          <h1 class="hero-title">PCB Design, Assembly &amp; <span class="hl">Chip Programming</span></h1>
          <p class="hero-subtitle">End-to-end electronics manufacturing support. Custom firmware flashing · PCB layout · Component sourcing. 48-hour turnaround guaranteed.</p>
          <div class="hero-ctas">
            <a href="chip-programming" class="btn btn-yellow btn-lg">Our Services</a>
            <a href="request-a-quote" class="btn btn-ghost-white btn-lg">Get a Quote</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 7 -->
    <div class="hero-slide hero-slide-7">
      <div class="wrap hero-slide-wrap">
        <div class="hero-content">
          <div class="hero-eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="2"/><path d="M16.24 7.76a6 6 0 010 8.49m-8.48-.01a6 6 0 010-8.49"/></svg>
            IoT · Embedded · Automation
          </div>
          <h1 class="hero-title">Top <span class="hl">Sensors</span> &amp; Communication ICs In Stock</h1>
          <p class="hero-subtitle">DHT22, MPU-6050, HC-SR04, nRF24L01+, ESP32 modules and 150+ more IoT components. Ready to ship same day.</p>
          <div class="hero-ctas">
            <a href="products?cat=sensor" class="btn btn-yellow btn-lg">Shop Sensors</a>
            <a href="products?cat=comm" class="btn btn-ghost-white btn-lg">Comm ICs</a>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /hero-track -->

  <!-- Hero Controls -->
  <button class="hero-prev" onclick="heroPrev()">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
  </button>
  <button class="hero-next" onclick="heroNext()">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
  </button>
  <div class="hero-dots">
    <button class="hero-dot on" onclick="heroGo(0)"></button>
    <button class="hero-dot" onclick="heroGo(1)"></button>
    <button class="hero-dot" onclick="heroGo(2)"></button>
    <button class="hero-dot" onclick="heroGo(3)"></button>
    <button class="hero-dot" onclick="heroGo(4)"></button>
    <button class="hero-dot" onclick="heroGo(5)"></button>
    <button class="hero-dot" onclick="heroGo(6)"></button>
  </div>
</div><!-- /hero -->


<!-- Deal Tabs -->
<div class="deals-bar">
  <div class="wrap">
    <div class="deals-bar-inner">
      <a class="deal-tab active" href="#flash-sales-section">Flash Sales</a>
      <a class="deal-tab" href="#best-seller-section">Best Seller</a>
      <a class="deal-tab" href="#popular-section">Popular</a>
      <a class="deal-tab" href="#new-arrival-section">New arrival</a>
      <div class="deal-timer">
        <span class="deal-timer-label">Ends in</span>
        <div class="timer-blocks">
          <span class="t-block" id="timerH">00</span><span class="t-sep">:</span>
          <span class="t-block" id="timerM">00</span><span class="t-sep">:</span>
          <span class="t-block" id="timerS">00</span>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ── Main Content ────────────────────────────────────────── -->
<div class="wrap page-wrap">

  <!-- Flash Sales -->
  <div class="home-section-wrap" id="flash-sales-section">
    <div class="sec-head">
      <div>
        <div class="sec-title">Flash Sales</div>
        <div class="sec-subtitle">Limited stock at special prices</div>
      </div>
      <div class="carousel-nav-btns">
        <button class="car-btn car-btn-inline" onclick="carouselScroll('flashDealsTrack', 1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="car-btn car-btn-inline" onclick="carouselScroll('flashDealsTrack', -1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>
    <div class="prod-carousel">
      <div class="prod-carousel-track-wrap">
        <div class="prod-carousel-track" id="flashDealsTrack"></div>
      </div>
    </div>
  </div>

  <!-- Popular -->
  <div class="home-section-wrap" id="popular-section">
    <div class="sec-head">
      <div>
        <div class="sec-title">Popular</div>
        <div class="sec-subtitle">Popular picks from top categories</div>
      </div>
      <div class="carousel-nav-btns">
        <button class="car-btn car-btn-inline" onclick="carouselScroll('featuredTrack', 1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="car-btn car-btn-inline" onclick="carouselScroll('featuredTrack', -1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>
    <div class="prod-carousel">
      <div class="prod-carousel-track-wrap">
        <div class="prod-carousel-track" id="featuredTrack"></div>
      </div>
    </div>
  </div>

  <!-- Top Deals -->
  <div class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">Top Deals This Week</div>
        <div class="sec-subtitle">Best-value picks trending this week</div>
      </div>
      <div class="carousel-nav-btns">
        <button class="car-btn car-btn-inline" onclick="carouselScroll('topDealsTrack', 1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="car-btn car-btn-inline" onclick="carouselScroll('topDealsTrack', -1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>
    <div class="prod-carousel">
      <div class="prod-carousel-track-wrap">
        <div class="prod-carousel-track" id="topDealsTrack"></div>
      </div>
    </div>
  </div>

  <!-- Best Seller -->
  <div class="home-section-wrap" id="best-seller-section">
    <div class="brand-banner brand-banner--ti">
      <div class="brand-logo-placeholder brand-logo--ti">TI</div>
      <div class="brand-tagline">
        <strong>Texas Instruments</strong>
        TI Authorized Distributor
      </div>
      <a href="products?mfr=TI" class="btn btn-outline btn-sm">Shop TI →</a>
    </div>
    <div class="sec-head">
      <div>
        <div class="sec-title">Best Seller</div>
        <div class="sec-subtitle">Genuine STM32, STM8, SPC series — from ₹285 per unit. Volume discounts available.</div>
      </div>
      <div class="carousel-nav-btns">
        <button class="car-btn car-btn-inline" onclick="carouselScroll('bestsellerTrack', 1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="car-btn car-btn-inline" onclick="carouselScroll('bestsellerTrack', -1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>
    <div class="prod-carousel">
      <div class="prod-carousel-track-wrap">
        <div class="prod-carousel-track" id="bestsellerTrack"></div>
      </div>
    </div>
  </div>

  <!-- New arrival -->
  <div class="home-section-wrap" id="new-arrival-section">
    <div class="sec-head">
      <div>
        <div class="sec-title">New arrival</div>
        <div class="sec-subtitle">Just landed this week</div>
      </div>
      <div class="carousel-nav-btns">
        <button class="car-btn car-btn-inline" onclick="carouselScroll('newArrivalsTrack', 1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="car-btn car-btn-inline" onclick="carouselScroll('newArrivalsTrack', -1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>
    <div class="prod-carousel">
      <div class="prod-carousel-track-wrap">
        <div class="prod-carousel-track" id="newArrivalsTrack"></div>
      </div>
    </div>
  </div>

  <!-- Trust Badges -->
  <div class="trust-badges">
    <div class="trust-badges-grid">
      <div>
        <div class="trust-badge-icon">🚚</div>
        <div class="trust-badge-title">Free Delivery</div>
        <div class="trust-badge-sub">On orders ₹5,000+</div>
      </div>
      <div>
        <div class="trust-badge-icon">✅</div>
        <div class="trust-badge-title">100% Genuine</div>
        <div class="trust-badge-sub">Authorised distributor</div>
      </div>
      <div>
        <div class="trust-badge-icon">↩️</div>
        <div class="trust-badge-title">Easy Returns</div>
        <div class="trust-badge-sub">7-day return policy</div>
      </div>
      <div>
        <div class="trust-badge-icon">🔒</div>
        <div class="trust-badge-title">Secure Payment</div>
        <div class="trust-badge-sub">UPI · Cards · COD · EMI</div>
      </div>
      <div>
        <div class="trust-badge-icon">📞</div>
        <div class="trust-badge-title">Expert Support</div>
        <div class="trust-badge-sub">Mon–Sat 9AM–6PM</div>
      </div>
      <div>
        <div class="trust-badge-icon">⚡</div>
        <div class="trust-badge-title">Same-Day Dispatch</div>
        <div class="trust-badge-sub">Orders before 2 PM</div>
      </div>
    </div>
  </div>

  <!-- Testimonials -->
  <div class="home-section-wrap">
    <div class="testimonials-hd">
      <div>
        <div class="sec-title">Customer Reviews</div>
        <div class="sec-subtitle">What our 50,000+ customers say about us</div>
      </div>
      <div class="rating-summary-box">
        <div class="rating-summary-num">4.8</div>
        <div class="rating-summary-stars">★★★★★</div>
        <div class="rating-summary-count">50K+ ratings</div>
      </div>
    </div>
    <div class="testimonials-grid">
      <div class="testimonial-card">
        <div class="testimonial-stars">★★★★★</div>
        <p class="testimonial-text">"Ordered STM32 chips and got them programmed with our custom firmware. Fast service, 100% genuine parts. Will order again!"</p>
        <div class="testimonial-author">Rajesh Kumar</div>
        <div class="testimonial-company">RK Electronics, Delhi · Verified Purchase</div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-stars">★★★★★</div>
        <p class="testimonial-text">"Excellent product quality and fast shipping. The chip programming service saved us 3 days of work. Highly recommended!"</p>
        <div class="testimonial-author">Priya Sharma</div>
        <div class="testimonial-company">TechMakers Lab, Bangalore · Verified Purchase</div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-stars">★★★★☆</div>
        <p class="testimonial-text">"Great range of components at competitive prices. Customer support is very helpful. My go-to store for all electronics."</p>
        <div class="testimonial-author">Mohammed Ali</div>
        <div class="testimonial-company">Robo Innovations, Hyderabad · Verified Purchase</div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-stars">★★★★★</div>
        <p class="testimonial-text">"We buy all our Arduino and ESP32 modules from Sinelec. Best prices, fastest delivery in India. 5 stars!"</p>
        <div class="testimonial-author">Anita Patel</div>
        <div class="testimonial-company">EduBot, Ahmedabad · Verified Purchase</div>
      </div>
    </div>
  </div>

  <!-- Newsletter -->
  <div class="newsletter-section">
    <div>
      <div class="newsletter-label">Newsletter</div>
      <div class="newsletter-title">Get Deals, New Products &amp; Tech Tips</div>
      <div class="newsletter-sub">Subscribe for exclusive offers, datasheets and application notes.</div>
    </div>
    <form id="nlForm" class="newsletter-form" novalidate>
      <input type="email" required placeholder="Enter your email address" class="newsletter-input">
      <button type="submit" class="btn btn-blue">Subscribe</button>
    </form>
  </div>

</div><!-- /wrap.page-wrap -->

</main>

<?php require_once 'footer.php'; ?>
