<?php
require_once __DIR__ . '/account-helpers.php';
$user = sinelec_require_login();
$currentPage = 'change-password';
$pageTitle = 'Change Password | Sinelec Technologies';
require_once __DIR__ . '/header.php';
?>

<main class="account-page">
  <div class="wrap">
    <div class="account-shell">
      <?php sinelec_render_account_nav($currentPage); ?>

      <section class="account-main">
        <article class="account-panel account-hero">
          <div>
            <span class="account-eyebrow">Security</span>
            <h1>Update your account password</h1>
            <p>Keep your procurement account protected with a strong password that is known only to the people who should manage orders, quotes, and delivery details.</p>
          </div>
          <div class="account-avatar">PW</div>
        </article>

        <div class="account-summary-grid">
          <article class="account-summary-card">
            <small>Signed-in Email</small>
            <strong><?= htmlspecialchars($user['EMAIL'] ?? '') ?></strong>
            <span>Your password update will apply to this account sign-in email.</span>
          </article>
          <article class="account-summary-card">
            <small>Password State</small>
            <strong><?= !empty($user['IS_PWD_UPDATED']) ? 'Already Updated' : 'Pending Update' ?></strong>
            <span><?= !empty($user['IS_PWD_UPDATED']) ? 'Your account is already using an updated password.' : 'It is a good time to refresh the password for better account security.' ?></span>
          </article>
          <article class="account-summary-card">
            <small>Recommendation</small>
            <strong>8+ characters</strong>
            <span>Use letters, numbers, and special characters for a stronger password.</span>
          </article>
        </div>

        <article class="account-panel">
          <div class="account-section-head">
            <div>
              <h2>Change Password</h2>
              <p>Enter your current password first, then choose a new secure password for the account.</p>
            </div>
          </div>

          <form method="POST" action="service?urlstring=<?= EncryptURL('action=ChangePassword') ?>">
            <div class="account-form-grid">
              <div class="account-field account-field--full">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
              </div>
              <div class="account-field">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$" required>
              </div>
              <div class="account-field">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
              </div>
            </div>
            <div class="account-form-actions">
              <button type="submit" class="account-btn">Update Password</button>
              <a href="profile" class="account-btn-secondary">Back to Profile</a>
            </div>
          </form>
        </article>

        <article class="account-note-card">
          <small>Security Tips</small>
          <ul>
            <li>Do not reuse the same password you use for email or personal accounts.</li>
            <li>Update your password whenever your internal purchasing team changes.</li>
            <li>Use the delivery address and order pages only from trusted devices and networks.</li>
          </ul>
        </article>
      </section>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
