<?php
require_once '../data/store_data.php';
$currentPage = 'about';
$pageTitle   = 'About Us — Sinelec Tech';
require_once 'header.php';
?>

<main>
<div class="wrap page-wrap">

  <!-- Page Header -->
  <div class="page-hero">
    <h1 class="page-title">About Sinelec Tech</h1>
    <p class="page-sub">India's trusted semiconductor &amp; electronic components distributor since 2015.</p>
  </div>

  <!-- Stats -->
  <div class="about-stats-grid">
    <div class="about-stat-card">
      <div class="about-stat-num">2015</div>
      <div class="about-stat-lbl">Founded</div>
    </div>
    <div class="about-stat-card">
      <div class="about-stat-num">50K+</div>
      <div class="about-stat-lbl">Happy Customers</div>
    </div>
    <div class="about-stat-card">
      <div class="about-stat-num">250K+</div>
      <div class="about-stat-lbl">Products</div>
    </div>
    <div class="about-stat-card">
      <div class="about-stat-num">15+</div>
      <div class="about-stat-lbl">Brand Partners</div>
    </div>
    <div class="about-stat-card">
      <div class="about-stat-num">28</div>
      <div class="about-stat-lbl">States Covered</div>
    </div>
  </div>

  <!-- Story -->
  <div class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">Our Story</div>
      </div>
    </div>
    <p class="about-body-text">Founded by a team of electronics engineers, Sinelec Tech started as a small component supplier in Delhi and has grown into one of India's most trusted online semiconductor stores. We serve hobbyists, engineering students, R&amp;D teams, startups, and manufacturers across the country.</p>
    <p class="about-body-text">Our mission: make genuine electronic components accessible to every Indian engineer, with expert support and fast delivery at competitive prices. Every product we sell is sourced directly from authorised distributors and carries a full manufacturer warranty.</p>
    <p class="about-body-text">Beyond components, we offer hands-on engineering services including chip programming, PCB design, and embedded development — so you get not just the parts but the expertise to use them effectively.</p>
  </div>

  <!-- Values -->
  <div class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">Why Engineers Choose Us</div>
        <div class="sec-subtitle">Our core commitments to every customer</div>
      </div>
    </div>
    <div class="trust-badges">
      <div class="trust-badges-grid">
        <div>
          <div class="trust-badge-icon">✅</div>
          <div class="trust-badge-title">100% Genuine</div>
          <div class="trust-badge-sub">Authorised distributor — zero counterfeits</div>
        </div>
        <div>
          <div class="trust-badge-icon">🚚</div>
          <div class="trust-badge-title">Fast Delivery</div>
          <div class="trust-badge-sub">Same-day dispatch, 1–4 day delivery</div>
        </div>
        <div>
          <div class="trust-badge-icon">💬</div>
          <div class="trust-badge-title">Expert Support</div>
          <div class="trust-badge-sub">Engineers on call Mon–Sat, 9AM–6PM</div>
        </div>
        <div>
          <div class="trust-badge-icon">💰</div>
          <div class="trust-badge-title">Best Pricing</div>
          <div class="trust-badge-sub">Volume discounts up to 40% off</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact -->
  <div id="contact" class="home-section-wrap">
    <div class="sec-head">
      <div>
        <div class="sec-title">Get in Touch</div>
        <div class="sec-subtitle">For product enquiries, bulk orders, or chip programming quotes — we respond within 24 hours.</div>
      </div>
    </div>
    <div class="contact-grid">

      <!-- Contact Info -->
      <div>
        <div class="contact-info-list">
          <div class="contact-info-item">
            <div class="contact-info-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 8.81"/></svg>
            </div>
            <div>
              <div class="contact-info-label">Phone</div>
              <div class="contact-info-val">+91-9876543210</div>
              <div class="contact-info-note">Mon–Sat, 9AM–6PM IST</div>
            </div>
          </div>
          <div class="contact-info-item">
            <div class="contact-info-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div>
              <div class="contact-info-label">Email</div>
              <div class="contact-info-val">info@sinelec-tech.com</div>
              <div class="contact-info-note">Reply within 24 hours</div>
            </div>
          </div>
          <div class="contact-info-item">
            <div class="contact-info-icon contact-info-icon--wa">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </div>
            <div>
              <div class="contact-info-label">WhatsApp</div>
              <div class="contact-info-val">+91-9876543210</div>
              <div class="contact-info-note">Quick response during hours</div>
            </div>
          </div>
          <div class="contact-info-item">
            <div class="contact-info-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
              <div class="contact-info-label">Address</div>
              <div class="contact-info-val">123, Electronics Market, Phase 2<br>Delhi, India — 110001</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="contact-form-wrap">
        <h3 class="contact-form-title">Send a Message</h3>
        <form id="contactForm" novalidate>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Your Name</label>
              <input type="text" class="form-inp" placeholder="Rajesh Kumar" required>
            </div>
            <div class="form-group">
              <label class="form-label">Phone</label>
              <input type="tel" class="form-inp" placeholder="+91 98765 43210" required>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" class="form-inp" placeholder="you@example.com" required>
          </div>
          <div class="form-group">
            <label class="form-label">Subject</label>
            <select class="form-inp">
              <option>Product Enquiry</option>
              <option>Chip Programming Service</option>
              <option>Bulk Order Quote</option>
              <option>PCB Design Service</option>
              <option>Technical Support</option>
              <option>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Message</label>
            <textarea class="form-inp textarea" placeholder="Describe your requirement in detail…" required></textarea>
          </div>
          <button type="submit" class="btn btn-blue contact-submit-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Send Message
          </button>
        </form>
      </div>
    </div>
  </div>

</div>
</main>

<?php require_once 'footer.php'; ?>
