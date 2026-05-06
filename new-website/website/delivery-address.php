<?php
require_once __DIR__ . '/account-helpers.php';
$user = sinelec_require_login();
$currentPage = 'delivery-address';
$pageTitle = 'Delivery Address | Sinelec Technologies';
require_once __DIR__ . '/header.php';
?>

<main class="account-page">
  <div class="wrap">
    <div class="account-shell">
      <?php sinelec_render_account_nav($currentPage); ?>

      <section class="account-main">
        <article class="account-panel account-hero">
          <div>
            <span class="account-eyebrow">Delivery Address</span>
            <h1>Manage your shipping locations</h1>
            <p>Save the business and project delivery addresses you use most often so quotes and checkout can move faster for your team.</p>
          </div>
          <div class="account-avatar">AD</div>
        </article>

        <article class="account-panel">
          <div class="account-section-head">
            <div>
              <h2>Saved Delivery Addresses</h2>
              <p>Your checkout and account pages share the same saved address list.</p>
            </div>
            <button type="button" class="account-btn-secondary" id="accountAddressRefreshBtn">Refresh</button>
          </div>
          <div class="account-address-grid" id="accountAddressGrid"></div>
        </article>

        <article class="account-panel">
          <div class="account-section-head">
            <div>
              <h2>Add New Address</h2>
              <p>Create a new delivery destination for procurement, warehouse, or project-specific shipments.</p>
            </div>
          </div>

          <form id="accountAddressForm">
            <div class="account-form-grid">
              <div class="account-field">
                <label for="accountAddrLabel">Address Label</label>
                <input type="text" id="accountAddrLabel" placeholder="Office, Lab, Warehouse">
              </div>
              <div class="account-field">
                <label for="accountAddrName">Contact Name</label>
                <input type="text" id="accountAddrName" value="<?= htmlspecialchars($user['NAME'] ?? '') ?>">
              </div>
              <div class="account-field">
                <label for="accountAddrPhone">Contact Phone</label>
                <input type="text" id="accountAddrPhone" value="+<?= htmlspecialchars((string)($user['COMMUNICATION_MOBILE_NUM_ISD'] ?? '')) ?> <?= htmlspecialchars((string)($user['COMMUNICATION_MOBILE_NUM'] ?? '')) ?>">
              </div>
              <div class="account-field">
                <label for="accountAddrPin">PIN / Postal Code</label>
                <input type="text" id="accountAddrPin" placeholder="110001">
              </div>
              <div class="account-field account-field--full">
                <label for="accountAddrLine">Full Address</label>
                <textarea id="accountAddrLine" placeholder="Building, street, city, state, country"></textarea>
              </div>
            </div>
            <div class="account-form-actions">
              <button type="submit" class="account-btn">Save Address</button>
              <button type="reset" class="account-btn-secondary">Reset</button>
            </div>
          </form>
        </article>
      </section>
    </div>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const grid = document.getElementById('accountAddressGrid');
  const form = document.getElementById('accountAddressForm');
  const refreshBtn = document.getElementById('accountAddressRefreshBtn');
  if (!grid || !form) return;

  const ADDRESS_KEY = 'sinelec_checkout_addresses';
  const SELECTED_KEY = 'sinelec_checkout_selected_address';

  function loadAddresses() {
    try {
      const stored = JSON.parse(localStorage.getItem(ADDRESS_KEY) || '[]');
      return Array.isArray(stored) ? stored : [];
    } catch {
      return [];
    }
  }

  function selectedId() {
    return localStorage.getItem(SELECTED_KEY) || '';
  }

  function saveAddresses(addresses, selected = '') {
    localStorage.setItem(ADDRESS_KEY, JSON.stringify(addresses));
    if (selected) {
      localStorage.setItem(SELECTED_KEY, selected);
    }
  }

  function render() {
    const addresses = loadAddresses();
    const activeId = selectedId();
    if (!addresses.length) {
      grid.innerHTML = `
        <article class="account-empty" style="grid-column:1 / -1;">
          <div class="account-empty-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <h3>No addresses saved yet</h3>
          <p>Add your first delivery destination to speed up future quotes and checkout.</p>
        </article>
      `;
      return;
    }

    grid.innerHTML = addresses.map(address => `
      <article class="account-address-card ${address.id === activeId ? 'is-selected' : ''}">
        <div class="account-address-top">
          <h3>${address.label || 'Saved Address'}</h3>
          <span class="account-address-badge">${address.badge || (address.id === activeId ? 'Selected' : 'Saved')}</span>
        </div>
        <p><strong>${address.name || ''}</strong></p>
        <p>${address.phone || ''}</p>
        <p>${address.line || ''}</p>
        <div class="account-address-actions">
          <button type="button" class="account-btn-secondary" data-address-select="${address.id}">Use This</button>
          <button type="button" class="account-btn-secondary" data-address-delete="${address.id}">Delete</button>
        </div>
      </article>
    `).join('');
  }

  form.addEventListener('submit', e => {
    e.preventDefault();
    const label = document.getElementById('accountAddrLabel')?.value.trim() || '';
    const name = document.getElementById('accountAddrName')?.value.trim() || '';
    const phone = document.getElementById('accountAddrPhone')?.value.trim() || '';
    const pin = document.getElementById('accountAddrPin')?.value.trim() || '';
    const line = document.getElementById('accountAddrLine')?.value.trim() || '';

    if (!label || !name || !phone || !pin || !line) {
      toast('Please fill all address fields.', 'warn');
      return;
    }

    const addresses = loadAddresses();
    const address = {
      id: `addr_${Date.now()}`,
      label,
      name,
      phone,
      line: `${line}${pin ? `, ${pin}` : ''}`,
      badge: 'Saved',
    };
    addresses.push(address);
    saveAddresses(addresses, address.id);
    form.reset();
    toast('Delivery address saved successfully.', 'pass');
    render();
  });

  grid.addEventListener('click', e => {
    const selectBtn = e.target.closest('[data-address-select]');
    const deleteBtn = e.target.closest('[data-address-delete]');
    const addresses = loadAddresses();

    if (selectBtn) {
      const nextId = selectBtn.getAttribute('data-address-select') || '';
      if (!nextId) return;
      saveAddresses(addresses, nextId);
      toast('Selected as your active delivery address.', 'pass');
      render();
      return;
    }

    if (deleteBtn) {
      const deleteId = deleteBtn.getAttribute('data-address-delete') || '';
      const filtered = addresses.filter(address => address.id !== deleteId);
      const nextSelected = filtered[0]?.id || '';
      saveAddresses(filtered, nextSelected);
      toast('Address removed.', 'warn');
      render();
    }
  });

  refreshBtn?.addEventListener('click', render);
  render();
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
