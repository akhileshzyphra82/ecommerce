<?php
$sinelaKnowledge = require __DIR__ . '/knowledges.php';
$assistant = $sinelaKnowledge['assistant'] ?? [];
$company = $sinelaKnowledge['company'] ?? [];
?>
<div class="sinela-chatbot" id="sinelaChatbot" data-auto-open="true">
  <button
    type="button"
    class="sinela-chatbot-fab"
    id="sinelaChatbotFab"
    aria-label="<?= htmlspecialchars($assistant['open_label'] ?? 'Open chatbot') ?>"
  >
    <span class="sinela-chatbot-fab-ring"></span>
    <span class="sinela-chatbot-fab-core">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <rect x="7" y="8" width="10" height="8" rx="3" stroke="currentColor" stroke-width="1.8"/>
        <path d="M9 5.5V4.2M15 5.5V4.2M4.8 10.2H3.5M20.5 10.2H19.2M6.2 18.2l-1 1M17.8 18.2l1 1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        <circle cx="10" cy="11.4" r="1" fill="currentColor"/>
        <circle cx="14" cy="11.4" r="1" fill="currentColor"/>
        <path d="M10 14c.6.5 1.2.8 2 .8s1.4-.3 2-.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M12 16v2.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
      </svg>
    </span>
  </button>

  <section class="sinela-chatbot-window" id="sinelaChatbotWindow" aria-label="<?= htmlspecialchars($assistant['name'] ?? 'Sinela AI') ?>" aria-hidden="true">
    <header class="sinela-chatbot-header">
      <div class="sinela-chatbot-brand">
        <span class="sinela-chatbot-brand-mark" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect x="7" y="8" width="10" height="8" rx="3" stroke="currentColor" stroke-width="1.8"/>
            <path d="M9 5.5V4.2M15 5.5V4.2M4.8 10.2H3.5M20.5 10.2H19.2M6.2 18.2l-1 1M17.8 18.2l1 1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <circle cx="10" cy="11.4" r="1" fill="currentColor"/>
            <circle cx="14" cy="11.4" r="1" fill="currentColor"/>
            <path d="M10 14c.6.5 1.2.8 2 .8s1.4-.3 2-.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M12 16v2.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </span>
        <div>
          <div class="sinela-chatbot-title"><?= htmlspecialchars($assistant['name'] ?? 'Sinela AI') ?></div>
          <div class="sinela-chatbot-status">
            <span class="sinela-chatbot-status-dot"></span>
            <?= htmlspecialchars($assistant['online_label'] ?? 'Online') ?>
          </div>
        </div>
      </div>
      <button
        type="button"
        class="sinela-chatbot-close"
        id="sinelaChatbotClose"
        aria-label="<?= htmlspecialchars($assistant['minimize_label'] ?? 'Minimize chat') ?>"
      >
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </header>

    <div class="sinela-chatbot-body">
      <div class="sinela-chatbot-messages" id="sinelaChatbotMessages"></div>
    </div>

    <form class="sinela-chatbot-inputbar" id="sinelaChatbotForm">
      <input
        type="text"
        id="sinelaChatbotInput"
        class="sinela-chatbot-input"
        placeholder="<?= htmlspecialchars($assistant['placeholder'] ?? 'Ask a question...') ?>"
        autocomplete="off"
      >
      <button type="submit" class="sinela-chatbot-send" aria-label="<?= htmlspecialchars($assistant['send_label'] ?? 'Send') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <line x1="22" y1="2" x2="11" y2="13" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          <polygon points="22 2 15 22 11 13 2 9 22 2" fill="currentColor"/>
        </svg>
      </button>
    </form>
  </section>
</div>

<script type="application/json" id="sinelaKnowledgeData"><?= json_encode($sinelaKnowledge, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
