<?php
require_once __DIR__ . '/account-helpers.php';
$user = sinelec_require_login();
$currentPage = 'profile';
$pageTitle = 'My Profile | Sinelec Technologies';
require_once __DIR__ . '/header.php';
?>

<main class="account-page">
  <div class="wrap">
    <div class="account-shell">
      <?php sinelec_render_account_nav($currentPage); ?>

      <section class="account-main">
        <article class="account-panel account-hero">
          <div>
            <span class="account-eyebrow">My Profile</span>
            <h1><?= htmlspecialchars($user['NAME'] ?? 'Sinelec Customer') ?></h1>
            <p>Your account workspace keeps your contact profile, order follow-ups, delivery details, and support access in one place so purchasing stays fast and organized.</p>
          </div>
          <div class="account-avatar"><?= htmlspecialchars(strtoupper(substr(sinelec_account_first_name($user), 0, 1))) ?></div>
        </article>

        <div class="account-summary-grid">
          <article class="account-summary-card">
            <small>Account Type</small>
            <strong><?= !empty($user['COMPANY_NAME']) ? 'Business Buyer' : 'Customer Account' ?></strong>
            <span><?= !empty($user['COMPANY_NAME']) ? htmlspecialchars($user['COMPANY_NAME']) : 'Personal account ready for quotes, orders, and support.' ?></span>
          </article>
          <article class="account-summary-card">
            <small>Phone</small>
            <strong>+<?= htmlspecialchars((string)($user['COMMUNICATION_MOBILE_NUM_ISD'] ?? '')) ?> <?= htmlspecialchars((string)($user['COMMUNICATION_MOBILE_NUM'] ?? '')) ?></strong>
            <span>Primary contact number used for order and quote updates.</span>
          </article>
          <article class="account-summary-card">
            <small>Password Status</small>
            <strong><?= !empty($user['IS_PWD_UPDATED']) ? 'Updated' : 'Needs Review' ?></strong>
            <span><?= !empty($user['IS_PWD_UPDATED']) ? 'Your sign-in password has already been updated.' : 'Review your password settings to keep the account secure.' ?></span>
          </article>
        </div>

        <article class="account-panel">
          <div class="account-section-head">
            <div>
              <h2>Account Details</h2>
              <p>A quick view of the information currently stored in your signed-in session.</p>
            </div>
          </div>
          <div class="account-detail-grid">
            <div class="account-detail-item">
              <label>Full Name</label>
              <div><?= htmlspecialchars($user['NAME'] ?? '') ?></div>
            </div>
            <div class="account-detail-item">
              <label>Email Address</label>
              <div><?= htmlspecialchars($user['EMAIL'] ?? '') ?></div>
            </div>
            <div class="account-detail-item">
              <label>Company Name</label>
              <div><?= htmlspecialchars($user['COMPANY_NAME'] ?: 'Not added yet') ?></div>
            </div>
            <div class="account-detail-item">
              <label>Designation</label>
              <div><?= htmlspecialchars($user['DESIGNATION'] ?: 'Not added yet') ?></div>
            </div>
            <div class="account-detail-item">
              <label>User ID</label>
              <div>#<?= htmlspecialchars((string)($user['USER_ID'] ?? '')) ?></div>
            </div>
            <div class="account-detail-item">
              <label>User Type</label>
              <div><?= htmlspecialchars((string)($user['USER_TYPE_ID'] ?? '')) ?></div>
            </div>
          </div>
        </article>

        <div class="account-card-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));">
          <article class="account-cta-card">
            <small>Need quick updates?</small>
            <strong style="display:block;font-size:20px;color:#132d4b;">Manage your delivery destinations</strong>
            <span>Keep your saved shipping addresses updated so checkout stays fast for repeat orders and quote requests.</span>
            <div class="account-form-actions">
              <a href="delivery-address" class="account-btn">Manage Addresses</a>
            </div>
          </article>
          <article class="account-cta-card">
            <small>Security</small>
            <strong style="display:block;font-size:20px;color:#132d4b;">Keep your account protected</strong>
            <span>Use the password page to update your credentials whenever your team access or buying contacts change.</span>
            <div class="account-form-actions">
              <a href="change-password" class="account-btn-secondary">Change Password</a>
            </div>
          </article>
        </div>
      </section>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
