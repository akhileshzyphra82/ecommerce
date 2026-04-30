<?php
require_once '../data/store_data.php';
$currentPage = 'checkout';
$pageTitle = 'Checkout - Sinelec Tech';
require_once 'header.php';
?>

<main>
  <div class="wrap page-wrap">
    <div class="page-hero checkout-hero">
      <div class="page-eyebrow">Secure Checkout</div>
      <h1 class="page-title">Complete Your Order</h1>
      <p class="page-sub">Review your delivery details, choose your address and payment method, and place your order with confidence.</p>
      <div class="checkout-hero-points">
        <span>100% genuine products</span>
        <span>Free delivery above ₹5,000</span>
        <span>Same-day dispatch before 2 PM</span>
      </div>
    </div>

    <div class="checkout-layout" id="checkoutPage">
      <div class="checkout-main">
        <section class="checkout-card">
          <div class="checkout-card-head">
            <div>
              <div class="checkout-card-title">Checkout Progress</div>
              <div class="checkout-card-sub">Everything you need to place the order smoothly.</div>
            </div>
          </div>
          <div class="checkout-progress">
            <div class="checkout-progress-step is-active">
              <span>1</span>
              <strong>Address</strong>
            </div>
            <div class="checkout-progress-step is-active">
              <span>2</span>
              <strong>Delivery</strong>
            </div>
            <div class="checkout-progress-step is-active">
              <span>3</span>
              <strong>Payment</strong>
            </div>
            <div class="checkout-progress-step is-active">
              <span>4</span>
              <strong>Review</strong>
            </div>
          </div>
        </section>

        <section class="checkout-card">
          <div class="checkout-card-head">
            <div>
              <div class="checkout-card-title">Select Delivery Address</div>
              <div class="checkout-card-sub">Choose a saved address or add a new delivery destination.</div>
            </div>
            <button type="button" class="checkout-link-btn" id="checkoutToggleAddressBtn">Change address</button>
          </div>
          <div class="checkout-card-body">
            <div class="checkout-address-grid" id="checkoutAddressGrid"></div>
            <div class="checkout-address-form-wrap hidden" id="checkoutAddressFormWrap">
              <div class="checkout-inline-note">Add or update an address for this order.</div>
              <form id="checkoutAddressForm" class="checkout-form-stack">
                <div class="form-row">
                  <div>
                    <label class="checkout-label">Address Label</label>
                    <input type="text" class="form-inp" id="checkoutAddrLabel" placeholder="Office / Warehouse / Home">
                  </div>
                  <div>
                    <label class="checkout-label">Contact Person</label>
                    <input type="text" class="form-inp" id="checkoutAddrName" placeholder="Receiver name">
                  </div>
                </div>
                <div class="form-row">
                  <div>
                    <label class="checkout-label">Phone Number</label>
                    <input type="tel" class="form-inp" id="checkoutAddrPhone" placeholder="+91 98765 43210">
                  </div>
                  <div>
                    <label class="checkout-label">PIN / ZIP</label>
                    <input type="text" class="form-inp" id="checkoutAddrPin" placeholder="110001">
                  </div>
                </div>
                <div>
                  <label class="checkout-label">Full Address</label>
                  <textarea class="form-inp textarea checkout-textarea-sm" id="checkoutAddrLine" placeholder="Building, street, area, city, state, country"></textarea>
                </div>
                <div class="checkout-form-actions">
                  <button type="submit" class="btn btn-blue">Save Address</button>
                  <button type="button" class="btn btn-outline" id="checkoutAddressCancelBtn">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </section>

        <section class="checkout-card">
          <div class="checkout-card-head">
            <div>
              <div class="checkout-card-title">Delivery Preferences</div>
              <div class="checkout-card-sub">Choose how fast you want the order processed and delivered.</div>
            </div>
          </div>
          <div class="checkout-card-body">
            <div class="checkout-choice-grid">
              <label class="checkout-choice-card">
                <input type="radio" name="checkoutShipping" value="standard" checked>
                <span class="checkout-choice-main">
                  <strong>Standard Delivery</strong>
                  <small>1-4 business days · Free above ₹5,000</small>
                </span>
                <span class="checkout-choice-tag">Recommended</span>
              </label>
              <label class="checkout-choice-card">
                <input type="radio" name="checkoutShipping" value="priority">
                <span class="checkout-choice-main">
                  <strong>Priority Dispatch</strong>
                  <small>Faster internal handling for urgent requirements</small>
                </span>
                <span class="checkout-choice-price">₹199</span>
              </label>
            </div>
            <div class="checkout-note-box">
              Orders placed before 2 PM may qualify for same-day dispatch when stock is available.
            </div>
          </div>
        </section>

        <section class="checkout-card">
          <div class="checkout-card-head">
            <div>
              <div class="checkout-card-title">Payment Method</div>
              <div class="checkout-card-sub">Choose the most convenient way to complete payment.</div>
            </div>
          </div>
          <div class="checkout-card-body">
            <div class="checkout-choice-grid checkout-choice-grid--payments">
              <label class="checkout-choice-card">
                <input type="radio" name="checkoutPayment" value="paypal" checked>
                <span class="checkout-choice-main">
                  <strong>PayPal</strong>
                  <small>Fast checkout with buyer protection</small>
                </span>
              </label>
              <label class="checkout-choice-card">
                <input type="radio" name="checkoutPayment" value="bank">
                <span class="checkout-choice-main">
                  <strong>Bank Transfer</strong>
                  <small>Best for company procurement and OEM orders</small>
                </span>
              </label>
              <label class="checkout-choice-card">
                <input type="radio" name="checkoutPayment" value="card">
                <span class="checkout-choice-main">
                  <strong>Credit / Debit Card</strong>
                  <small>Visa, Mastercard, American Express</small>
                </span>
              </label>
            </div>
            <div class="form-row">
              <div>
                <label class="checkout-label">PO Number <span class="checkout-optional">(optional)</span></label>
                <input type="text" class="form-inp" id="checkoutPoNumber" placeholder="Reference for your records">
              </div>
              <div>
                <label class="checkout-label">Email for Invoice</label>
                <input type="email" class="form-inp" id="checkoutInvoiceEmail" placeholder="billing@company.com">
              </div>
            </div>
          </div>
        </section>

        <section class="checkout-card">
          <div class="checkout-card-head">
            <div>
              <div class="checkout-card-title">Order Notes</div>
              <div class="checkout-card-sub">Share any delivery, packaging, firmware, or project-specific instructions.</div>
            </div>
          </div>
          <div class="checkout-card-body">
            <textarea class="form-inp textarea" id="checkoutOrderNotes" placeholder="Example: Deliver during office hours, include compliance documents, or match the quote reference."></textarea>
          </div>
        </section>
      </div>

      <aside class="checkout-summary">
        <section class="checkout-card checkout-card--sticky">
          <div class="checkout-card-head">
            <div>
              <div class="checkout-card-title">Order Summary</div>
              <div class="checkout-card-sub">A quick review before you place the order.</div>
            </div>
          </div>
          <div class="checkout-card-body">
            <div class="checkout-empty hidden" id="checkoutEmptyState">
              <p>Your cart is empty right now.</p>
              <a href="products" class="btn btn-blue">Browse Products</a>
            </div>

            <div id="checkoutSummaryContent">
              <div class="checkout-summary-items" id="checkoutSummaryItems"></div>

              <div class="checkout-summary-meta">
                <div class="checkout-summary-row">
                  <span>Delivering to</span>
                  <strong id="checkoutSelectedAddressText">Select an address</strong>
                </div>
                <div class="checkout-summary-row">
                  <span>Delivery mode</span>
                  <strong id="checkoutShippingText">Standard Delivery</strong>
                </div>
                <div class="checkout-summary-row">
                  <span>Payment</span>
                  <strong id="checkoutPaymentText">PayPal</strong>
                </div>
              </div>

              <div class="checkout-totals">
                <div class="checkout-summary-row"><span>Subtotal</span><strong id="checkoutSubtotal">₹0.00</strong></div>
                <div class="checkout-summary-row"><span>Shipping</span><strong id="checkoutShippingCost">₹0.00</strong></div>
                <div class="checkout-summary-row"><span>GST (18%)</span><strong id="checkoutTax">₹0.00</strong></div>
                <div class="checkout-summary-row checkout-summary-row--total"><span>Order Total</span><strong id="checkoutTotal">₹0.00</strong></div>
              </div>

              <button type="button" class="cart-checkout-btn checkout-place-btn" id="checkoutPlaceBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Place Order
              </button>
              <p class="checkout-secure-line">Secure checkout · Tax invoice available · Support Mon-Sat, 9 AM-6 PM</p>
            </div>
          </div>
        </section>
      </aside>
    </div>
  </div>
</main>

<?php require_once 'footer.php'; ?>
