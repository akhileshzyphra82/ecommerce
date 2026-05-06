<?php
require_once __DIR__ . '/account-helpers.php';
$user = sinelec_require_login();
$currentPage = 'my-orders';
$pageTitle = 'My Orders | Sinelec Technologies';
require_once __DIR__ . '/header.php';
?>

<main class="account-page">
  <div class="wrap">
    <div class="account-shell">
      <?php sinelec_render_account_nav($currentPage); ?>

      <section class="account-main">
        <article class="account-panel account-hero">
          <div>
            <span class="account-eyebrow">Orders</span>
            <h1>Track your purchasing activity</h1>
            <p>Review confirmed orders, monitor dispatch progress, and keep your procurement records organized for repeat semiconductor and component buying.</p>
          </div>
          <div class="account-avatar">PO</div>
        </article>

        <div class="account-summary-grid">
          <article class="account-summary-card">
            <small>Orders Placed</small>
            <strong>0</strong>
            <span>Your account is ready to start tracking upcoming orders.</span>
          </article>
          <article class="account-summary-card">
            <small>Dispatch Priority</small>
            <strong>Same-day</strong>
            <span>Orders placed before 2 PM can be dispatched the same business day when items are in stock.</span>
          </article>
          <article class="account-summary-card">
            <small>Support Window</small>
            <strong>Mon - Sat</strong>
            <span>9 AM to 6 PM support for order coordination, quotes, and delivery follow-up.</span>
          </article>
        </div>

        <article class="account-empty">
          <div class="account-empty-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
          </div>
          <h3>No orders yet</h3>
          <p>Once you complete a checkout or confirm a quotation-based order, your order history will appear here with dispatch and delivery details.</p>
          <div class="account-empty-actions">
            <a href="products" class="account-btn">Browse Products</a>
            <a href="request-a-quote" class="account-btn-secondary">Request a Quote</a>
          </div>
        </article>

        <article class="account-note-card">
          <small>How this area helps</small>
          <ul>
            <li>View upcoming order milestones like confirmation, dispatch, and shipment tracking.</li>
            <li>Keep procurement references aligned with your account email and delivery address.</li>
            <li>Coordinate custom programming or bulk-order follow-up with the Sinelec support team.</li>
          </ul>
        </article>
      </section>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
