<?php
require_once '../data/store_data.php';
$currentPage = 'resources';
$pageTitle   = 'Shipping and Payment Terms — Sinelec Tech';
require_once 'header.php';

$shippingRows = [
    [
        'region' => 'Germany',
        'cost' => '4,99€',
        'service' => 'DPD',
        'payments' => ['Paypal', 'Credit Card via Paypal (No Paypal Account needed)', 'Bank Transfer', 'Invoice (Corporate customers)'],
        'stock' => '1-2 Workdays',
        'outStock' => '4-5 Workdays',
    ],
    [
        'region' => 'Austria, Belgium, Bulgaria, Croatia, Czech Republic, Denmark, Estonia, Finland, France, Hungary, Ireland, Italy, Latvia, Lithuania, Luxembourg, Malta, Monaco, Netherlands, Poland, Portugal, Romania, Slovakia, Slovenia, Spain, Sweden and the UK',
        'cost' => '12,99€',
        'service' => 'DPD / UPS',
        'payments' => ['Paypal', 'Credit Card via Paypal (No Paypal Account needed)', 'Bank Transfer', 'Invoice (Corporate customers)'],
        'stock' => '2-3 Workdays',
        'outStock' => '4-7 Workdays',
    ],
    [
        'region' => 'Switzerland',
        'cost' => '19,99€',
        'service' => 'UPS',
        'payments' => ['Paypal', 'Credit Card via Paypal (No Paypal Account needed)', 'Bank Transfer', 'Invoice (Corporate customers)'],
        'stock' => '2-4 Workdays',
        'outStock' => '4-7 Workdays',
    ],
    [
        'region' => 'Rest of the World',
        'cost' => '19,99€',
        'service' => 'UPS',
        'payments' => ['Paypal', 'Credit Card via Paypal (No Paypal Account needed)', 'Bank Transfer', 'Invoice (Corporate customers)'],
        'stock' => '7-10 Workdays',
        'outStock' => '11-14 Workdays',
    ],
];
?>

<main>
  <div class="wrap page-wrap">

    <section class="shipterm-hero">
      <div class="shipterm-hero-copy">
        <div class="shipterm-eyebrow">Order Policy</div>
        <h1 class="shipterm-title">Shipping and Payment</h1>
        <p class="shipterm-sub">Clear delivery timelines, accepted payment methods, VAT handling, and region-wise shipping guidance for every Sinelec Technologies order.</p>
        <div class="shipterm-hero-points">
          <div class="shipterm-chip">Same-day shipping for in-stock orders placed before 14:00 CET</div>
          <div class="shipterm-chip">Tracking email and invoice shared after dispatch</div>
          <div class="shipterm-chip">Corporate-friendly payment and VAT support</div>
        </div>
      </div>
      <div class="shipterm-hero-card">
        <div class="shipterm-hero-stat">
          <strong>14:00 CET</strong>
          <span>Cut-off for same-day shipment on business days</span>
        </div>
        <div class="shipterm-hero-stat">
          <strong>2 emails</strong>
          <span>Order confirmation first, shipment tracking second</span>
        </div>
        <div class="shipterm-hero-stat">
          <strong>Global delivery</strong>
          <span>Germany, EU, Switzerland, and Rest of World coverage</span>
        </div>
      </div>
    </section>

    <section class="shipterm-section">
      <div class="sec-head">
        <div>
          <div class="sec-title">Order Processing</div>
          <div class="sec-subtitle">How we move your order from confirmation to dispatch</div>
        </div>
      </div>
      <div class="shipterm-grid shipterm-grid--three">
        <article class="shipterm-info-card">
          <div class="shipterm-icon-badge shipterm-icon-badge--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2Z"/><path d="M3 10h18"/><path d="M7 14h4"/></svg>
          </div>
          <h3>Place your order</h3>
          <p>You can order directly through the webshop or ask our team for a quotation through the Request Quote flow.</p>
        </article>
        <article class="shipterm-info-card">
          <div class="shipterm-icon-badge shipterm-icon-badge--gold">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </div>
          <h3>Processing speed</h3>
          <p>When all ordered products are in stock and the order is placed before 14.00 hours (CET) on a business day, we ship the same day.</p>
        </article>
        <article class="shipterm-info-card">
          <div class="shipterm-icon-badge shipterm-icon-badge--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 12s-4-8-10-8S2 12 2 12s4 8 10 8 10-8 10-8Z"/><circle cx="12" cy="12" r="3"/></svg>
          </div>
          <h3>Exception handling</h3>
          <p>If an order cannot be processed immediately, a Sinelec team member will contact you and guide you on the next step.</p>
        </article>
      </div>

      <div class="shipterm-grid shipterm-grid--two">
        <article class="shipterm-process-card">
          <div class="shipterm-process-head">
            <span class="shipterm-step">01</span>
            <h3>Order confirmation email</h3>
          </div>
          <p>After placing your order, we send an email confirming the order along with a copy of your invoice.</p>
        </article>
        <article class="shipterm-process-card">
          <div class="shipterm-process-head">
            <span class="shipterm-step">02</span>
            <h3>Shipment email</h3>
          </div>
          <p>When your order is shipped, we send a second email with the tracking code and a soft copy of the invoice.</p>
        </article>
      </div>
    </section>

    <section class="shipterm-section">
      <div class="sec-head">
        <div>
          <div class="sec-title">Payment Information</div>
          <div class="sec-subtitle">Payment methods for webshop orders, quotations, and company purchase orders</div>
        </div>
      </div>
      <div class="shipterm-grid shipterm-grid--two">
        <article class="shipterm-payment-card">
          <h3>Webshop payment methods</h3>
          <ul class="shipterm-list">
            <li>Paypal</li>
            <li>Credit Card via Paypal (No Paypal Account Needed)</li>
          </ul>
        </article>
        <article class="shipterm-payment-card shipterm-payment-card--accent">
          <h3>Quotation or company purchase order</h3>
          <ul class="shipterm-list">
            <li>Bank Transfer in our German Bank Account</li>
            <li>Invoice (Only for Corporate Customer)</li>
            <li>Paypal</li>
          </ul>
        </article>
      </div>
    </section>

    <section class="shipterm-section">
      <div class="sec-head">
        <div>
          <div class="sec-title">VAT and International Ordering</div>
          <div class="sec-subtitle">How VAT is handled for EU business customers and international shipments</div>
        </div>
      </div>
      <div class="shipterm-grid shipterm-grid--two">
        <article class="shipterm-policy-card">
          <h3>Foreign EU company VAT exempted ordering</h3>
          <p>If you order for a company in the EU, except Germany, and you have a valid EU VAT number, German VAT does not apply. Supply your valid EU VAT number during webshop checkout or while requesting a quotation.</p>
          <div class="shipterm-note-box">If an order is placed with an invalid EU VAT number and we need to correct it afterwards, we charge 5€ administrative costs.</div>
          <ol class="shipterm-steps-list">
            <li>Create an account.</li>
            <li>Enter your valid EU VAT number while ordering through the webshop.</li>
            <li>Place your order and the webshop will stop calculating taxes.</li>
            <li>If buying through quotation or your company purchase order, share the valid EU VAT number so we can issue a VAT-free quotation and/or invoice.</li>
          </ol>
        </article>
        <article class="shipterm-policy-card">
          <h3>International customers outside the EU</h3>
          <p>If you are an international customer living or shipping outside the EU, German taxes do not apply. When your account address is outside the EU, end order prices are displayed excluding German VAT.</p>
          <p>Please note that taxes may still be calculated when your order passes customs in your destination country.</p>
          <div class="shipterm-policy-foot">
            <strong>Need help before ordering?</strong>
            <span>Reach out through Request Quote and our team can confirm the right billing flow before you pay.</span>
          </div>
        </article>
      </div>
    </section>

    <section class="shipterm-section">
      <div class="sec-head">
        <div>
          <div class="sec-title">Shipping Cost and Delivery Information</div>
          <div class="sec-subtitle">Region-wise shipping service, payment availability, and expected delivery times</div>
        </div>
      </div>

      <div class="shipterm-table-wrap">
        <table class="shipterm-table">
          <thead>
            <tr>
              <th>Country / Region</th>
              <th>Shipping Cost</th>
              <th>Shipping Service</th>
              <th>Payment Methods</th>
              <th>Delivery Time (In Stock Product)</th>
              <th>Delivery Time (Out of Stock Product)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($shippingRows as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['region']) ?></td>
              <td><strong><?= htmlspecialchars($row['cost']) ?></strong></td>
              <td><?= htmlspecialchars($row['service']) ?></td>
              <td>
                <ul class="shipterm-table-list">
                  <?php foreach ($row['payments'] as $payment): ?>
                  <li><?= htmlspecialchars($payment) ?></li>
                  <?php endforeach; ?>
                </ul>
              </td>
              <td><?= htmlspecialchars($row['stock']) ?></td>
              <td><?= htmlspecialchars($row['outStock']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="shipterm-mobile-cards">
        <?php foreach ($shippingRows as $row): ?>
        <article class="shipterm-region-card">
          <div class="shipterm-region-head">
            <h3><?= htmlspecialchars($row['region']) ?></h3>
            <span><?= htmlspecialchars($row['cost']) ?></span>
          </div>
          <div class="shipterm-region-grid">
            <div>
              <small>Shipping service</small>
              <strong><?= htmlspecialchars($row['service']) ?></strong>
            </div>
            <div>
              <small>In stock</small>
              <strong><?= htmlspecialchars($row['stock']) ?></strong>
            </div>
            <div>
              <small>Out of stock</small>
              <strong><?= htmlspecialchars($row['outStock']) ?></strong>
            </div>
          </div>
          <div class="shipterm-region-payments">
            <small>Payment methods</small>
            <ul class="shipterm-list">
              <?php foreach ($row['payments'] as $payment): ?>
              <li><?= htmlspecialchars($payment) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

  </div>
</main>

<?php require_once 'footer.php'; ?>
