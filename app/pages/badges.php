<?php
declare(strict_types=1);
$b = $T['badges'];
$badges = require APP_ROOT . '/data/badges.php';
?>
<section class="page-hero page-hero--badges">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $b['hero']['eyebrow'] ?></p>
    <h1><?= $b['hero']['title'] ?></h1>
    <p class="lede"><?= e($b['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($b['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
    <div class="actions mt-m">
      <?= btn(e($b['hero']['primary']), '/assets/kit/aifriendly-badge-kit.zip', 'primary', 'download') ?>
      <?= btn(e($b['hero']['secondary']), '#guidelines', 'secondary') ?>
    </div>

    <div class="badge-preview" aria-hidden="true">
      <div class="badge-preview__tile badge-preview__tile--paper"><img src="/assets/badges/lockup-ink.svg" width="159" height="44" alt=""></div>
      <div class="badge-preview__tile badge-preview__tile--ink"><img src="/assets/badges/lockup-cream.svg" width="159" height="44" alt=""></div>
      <div class="badge-preview__tile badge-preview__tile--spectrum"><img src="/assets/badges/lockup-flat-cream.svg" width="159" height="44" alt=""></div>
    </div>
  </div>
</section>

<section class="section section--flush" id="badges">
  <div class="wrap">
    <?= section_head($b['section']['eyebrow'], $b['section']['title'], $b['section']['lede']) ?>
    <div class="badge-grid">
      <?php foreach ($badges as $badge): ?>
      <article class="badge-card">
        <div class="badge-stage">
          <div class="badge-stage__dark"><img src="<?= e($badge['preview_dark']) ?>" width="<?= (int) $badge['thumb_w'] ?>" height="<?= (int) $badge['thumb_h'] ?>" alt="<?= e($badge['title']) ?>" loading="lazy" decoding="async"></div>
          <div class="badge-stage__light"><img src="<?= e($badge['preview_light']) ?>" width="<?= (int) $badge['thumb_w'] ?>" height="<?= (int) $badge['thumb_h'] ?>" alt="<?= e($badge['title']) ?>" loading="lazy" decoding="async"></div>
        </div>
        <div class="badge-card__body">
          <div class="badge-card__title"><h3><?= e($badge['title']) ?></h3><span><?= e($badge['format']) ?></span></div>
          <p><?= e(tr($badge['desc'])) ?></p>
          <?php if (!empty($badge['verified'])): ?>
          <p class="badge-card__note"><?= e($b['labels']['verified_only']) ?></p>
          <div class="badge-actions">
            <a href="<?= e(url('/verify/')) ?>"><?= e($b['labels']['apply']) ?><?= arrow_icon() ?></a>
            <a href="<?= e(url('/license/')) ?>"><?= e(t('common.footer.license')) ?></a>
          </div>
          <?php else: ?>
          <div class="badge-actions">
            <?php foreach ($badge['files'] as [$label, $path]): ?>
            <a href="<?= e($path) ?>" download><?= e($label) ?></a>
            <?php endforeach; ?>
          </div>
          <div class="badge-actions badge-actions--tools">
            <button type="button" data-copy-target="#snip-<?= e($badge['id']) ?>-html"><?= copy_icon() ?><?= e($b['labels']['copy_html']) ?></button>
            <button type="button" data-copy-target="#snip-<?= e($badge['id']) ?>-md"><?= e($b['labels']['copy_md']) ?></button>
            <button type="button" data-toggle-target="#snip-<?= e($badge['id']) ?>-html" aria-expanded="false"><?= e($b['labels']['view']) ?></button>
          </div>
          <pre tabindex="0" class="snippet" id="snip-<?= e($badge['id']) ?>-html"><?= e($badge['html']) ?></pre>
          <pre tabindex="0" class="snippet" id="snip-<?= e($badge['id']) ?>-md"><?= e($badge['md']) ?></pre>
          <?php endif; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="note mt-l"><p><?= links($b['note']) ?></p></div>
  </div>
</section>

<section class="section section--tint" id="choose">
  <div class="wrap">
    <?= section_head($b['choose']['eyebrow'], $b['choose']['title'], $b['choose']['lede']) ?>
    <div class="grid-3">
      <?php foreach ($b['choose']['items'] as [$micro, $title, $text]): ?>
      <article class="info-card">
        <p class="micro"><?= e($micro) ?></p>
        <h3><?= e($title) ?></h3>
        <p><?= e($text) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="guidelines">
  <div class="wrap">
    <?= section_head($b['guidelines']['eyebrow'], $b['guidelines']['title'], $b['guidelines']['lede']) ?>
    <div class="guideline-grid">
      <?php foreach ($b['guidelines']['items'] as [$title, $text]): ?>
      <div class="guideline"><h3><?= e($title) ?></h3><p><?= links($text) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tint" id="templates">
  <div class="wrap">
    <?= section_head($b['templates']['eyebrow'], $b['templates']['title'], $b['templates']['lede']) ?>
    <div class="resource-grid">
      <?php foreach ($b['templates']['items'] as $item): [$micro, $title, $text, $l1, $h1, $l2, $h2] = $item; $l3 = $item[7] ?? ''; $h3 = $item[8] ?? ''; $l4 = $item[9] ?? ''; $h4 = $item[10] ?? ''; ?>
      <article class="resource-card">
        <p class="micro"><?= e($micro) ?></p>
        <h3><?= e($title) ?></h3>
        <p><?= e($text) ?></p>
        <div class="resource-card__files">
          <a href="<?= e($h1) ?>" download><?= e($l1) ?><?= arrow_icon() ?></a>
          <?php if ($h2 !== ''): ?><a href="<?= e($h2) ?>" download><?= e($l2) ?><?= arrow_icon() ?></a><?php endif; ?>
          <?php if ($h3 !== ''): ?><a href="<?= e($h3) ?>" download><?= e($l3) ?><?= arrow_icon() ?></a><?php endif; ?>
          <?php if ($h4 !== ''): ?><a href="<?= e($h4) ?>" download><?= e($l4) ?><?= arrow_icon() ?></a><?php endif; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--cta">
  <div class="wrap">
    <div class="cta-band">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($b['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($b['cta']['title']) ?></h2>
      <p class="lede"><?= e($b['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($b['cta']['primary']), url('/standard/#audit'), 'primary') ?>
        <?= btn(e($b['cta']['secondary']), url('/scan/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>
