<?php
declare(strict_types=1);
$g = $T['gallery'];
?>
<section class="page-hero">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($g['hero']['eyebrow']) ?></p>
    <h1><?= e($g['hero']['title']) ?></h1>
    <p class="lede"><?= e($g['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($g['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--flush">
  <div class="wrap">
    <h2 class="sr-only"><?= e($g['grid_title']) ?></h2>
    <div class="placements placements--six">
      <?php foreach ($g['items'] as [$label, $context, $type]): ?>
      <article class="placement placement--<?= e($type) ?>">
        <div class="placement__stage">
          <?php if ($type === 'site'): ?>
          <div class="mock mock--site" aria-hidden="true">
            <div class="mock__bar"><i></i><i></i><i></i><span>fieldnotes.example</span></div>
            <div class="mock__body">
              <p class="mock__kicker">FIELD NOTES / 014</p>
              <p class="mock__title">On making things slowly</p>
              <span class="mock__line"></span><span class="mock__line"></span><span class="mock__line mock__line--short"></span>
              <div class="mock__footer"><span>© 2026</span><img loading="lazy" decoding="async" src="/assets/badges/lockup-ink.svg" width="96" height="27" alt=""></div>
            </div>
          </div>
          <?php elseif ($type === 'phone'): ?>
          <div class="mock mock--phone" aria-hidden="true">
            <div class="mock__screen"><span class="mock__notch"></span><img loading="lazy" decoding="async" class="mock__appicon" src="/assets/badges/icon-paper.svg" width="36" height="36" alt=""><p class="mock__title">Fieldnotes</p><span class="mock__line"></span><span class="mock__line mock__line--short"></span><img loading="lazy" decoding="async" class="mock__badge" src="/assets/badges/lockup-ink.svg" width="92" height="26" alt=""></div>
          </div>
          <?php elseif ($type === 'poster'): ?>
          <div class="mock mock--poster" aria-hidden="true"><img loading="lazy" decoding="async" src="/assets/brand/mark-poster.svg" width="120" height="120" alt=""><span>AI FRIENDLY</span></div>
          <?php elseif ($type === 'readme'): ?>
          <div class="mock mock--readme" aria-hidden="true">
            <div class="mock__bar"><i></i><i></i><i></i><span>README.md</span></div>
            <div class="mock__body"><p class="mock__kicker"># fieldnotes</p><p class="mock__title">A calm, local-first notebook.</p><span class="mock__line"></span><span class="mock__line mock__line--short"></span><div class="mock__footer"><span>[![AI Friendly](badge.svg)](https://aifriendly.fyi/)</span><img loading="lazy" decoding="async" src="/assets/badges/lockup-ink.svg" width="84" height="23" alt=""></div></div>
          </div>
          <?php elseif ($type === 'slide'): ?>
          <div class="mock mock--slide" aria-hidden="true"><p class="micro">FIELDNOTES / 2026</p><p class="mock-slide__title">Built with AI.<br>Reviewed by humans.</p><img loading="lazy" decoding="async" src="/assets/badges/seal-cream.svg" width="128" height="128" alt=""></div>
          <?php else: ?>
          <div class="mock mock--sticker" aria-hidden="true"><img loading="lazy" decoding="async" src="/assets/badges/icon-paper.svg" width="64" height="64" alt=""><span>AI FRIENDLY</span><small>aifriendly.fyi</small></div>
          <?php endif; ?>
        </div>
        <div class="placement__meta"><span><?= e($label) ?></span><span><?= e($context) ?></span></div>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="note mt-l"><p><?= links($g['note']) ?></p></div>
  </div>
</section>

<section class="section section--tint" id="submit">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($g['submit']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($g['submit']['title']) ?></h2>
      <p class="lede"><?= e($g['submit']['lede']) ?></p>
      <div class="note mt-m"><p><?= links($g['submit']['note']) ?></p></div>
    </div>
    <form class="form reveal d1" method="post" action="/api/contact.php" data-form="gallery">
      <?= honeypot() ?>
      <input type="hidden" name="type" value="gallery">
      <div class="form-row">
        <?= field('name', t('common.forms.name'), 'text', 'autocomplete="name" maxlength="120" required') ?>
        <?= field('email', t('common.forms.email'), 'email', 'autocomplete="email" maxlength="200" required') ?>
      </div>
      <?= field('url', t('common.forms.url'), 'url', 'inputmode="url" placeholder="https://" maxlength="300" required') ?>
      <?= field('message', t('common.forms.placement'), 'textarea', 'maxlength="1000" required') ?>
      <div class="actions">
        <button class="btn btn--primary" type="submit"><?= e(t('common.cta.submit')) ?><?= arrow_icon() ?></button>
        <span class="form-msg" data-form-msg aria-live="polite"></span>
      </div>
    </form>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="cta-band">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($g['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($g['cta']['title']) ?></h2>
      <p class="lede"><?= e($g['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($g['cta']['primary']), url('/badges/'), 'primary') ?>
        <?= btn(e($g['cta']['secondary']), url('/resources/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>
