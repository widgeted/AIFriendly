<?php
declare(strict_types=1);
$s = $T['scan'];
$scanI18n = [
    'reading' => t('common.scan.reading'),
    'result' => t('common.scan.result'),
    'of' => t('common.scan.of'),
    'readable' => t('common.scan.readable'),
    'discoverable' => t('common.scan.discoverable'),
    'operable' => t('common.scan.operable'),
    'trust' => t('common.scan.trust'),
    'copy' => t('common.scan.copy'),
    'button' => t('common.scan.button'),
    'scanning' => t('common.scan.scanning'),
    'fix' => t('common.scan.fail'),
    'standard' => t('common.scan.standard'),
    'pass' => t('common.scan.pass'),
    'warn' => t('common.scan.warn'),
    'fail' => t('common.scan.fail'),
    'complete' => t('common.scan.complete'),
    'error' => t('common.scan.error'),
    'copied' => t('common.scan.copied'),
    'copy_failed' => t('common.scan.copy_failed'),
    'grades' => $T['common']['scan']['grades'],
    'summaries' => $T['common']['scan']['summaries'],
    'standard_check' => t('common.scan.standard_check'),
];
?>
<section class="page-hero page-hero--scan">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $s['hero']['eyebrow'] ?></p>
    <h1><?= $s['hero']['title'] ?></h1>
    <p class="lede"><?= e($s['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($s['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
    <form class="scan-form scan-form--page" action="<?= e(url('/scan/')) ?>" method="get" data-scan-form novalidate>
      <label class="sr-only" for="scan-url"><?= e(t('common.scan.placeholder')) ?></label>
      <input id="scan-url" name="url" type="text" inputmode="url" autocomplete="url" placeholder="<?= e(t('common.scan.placeholder')) ?>" required>
      <button class="btn btn--primary" type="submit" data-scan-submit><span data-scan-label><?= e(t('common.scan.button')) ?></span><?= arrow_icon() ?></button>
    </form>
    <p class="micro mt-s"><?= e(t('common.scan.micro')) ?></p>
    <noscript><p class="note mt-m"><?= links($s['form']['noscript']) ?></p></noscript>

    <div class="scan-result" data-scan-result hidden>
      <div class="scan-loading" data-scan-loading hidden><span class="spinner" aria-hidden="true"></span><span><?= e(t('common.scan.reading')) ?></span></div>
      <div class="scan-error" data-scan-error hidden role="alert"></div>

      <div data-scan-output hidden>
        <div class="score-card">
          <div class="score-ring">
            <svg viewBox="0 0 120 120" aria-hidden="true">
              <circle class="score-ring__bg" cx="60" cy="60" r="54"></circle>
              <circle class="score-ring__fg" cx="60" cy="60" r="54" stroke-dasharray="339.292" stroke-dashoffset="339.292" data-score-ring></circle>
            </svg>
            <div class="score-ring__num"><strong data-score-num>0</strong><span><?= e(t('common.scan.of')) ?></span></div>
          </div>
          <div class="score-headline">
            <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e(t('common.scan.result')) ?></p>
            <h2 data-score-grade>—</h2>
            <p data-score-summary>—</p>
            <p class="score-url" data-score-url></p>
            <div class="cats" data-cats>
              <div class="cat"><span><?= e(t('common.scan.readable')) ?></span><b data-cat-label="read">—</b><i><em data-cat="read"></em></i></div>
              <div class="cat"><span><?= e(t('common.scan.discoverable')) ?></span><b data-cat-label="discover">—</b><i><em data-cat="discover"></em></i></div>
              <div class="cat"><span><?= e(t('common.scan.operable')) ?></span><b data-cat-label="operate">—</b><i><em data-cat="operate"></em></i></div>
              <div class="cat"><span><?= e(t('common.scan.trust')) ?></span><b data-cat-label="trust">—</b><i><em data-cat="trust"></em></i></div>
            </div>
            <div class="actions">
              <button class="btn btn--secondary btn--sm" type="button" data-scan-copy><?= copy_icon() ?><?= e(t('common.scan.copy')) ?></button>
              <?= btn(e(t('common.scan.standard')), url('/standard/'), 'secondary btn--sm') ?>
            </div>
          </div>
        </div>
        <div class="result-groups" data-result-groups></div>
        <p class="scan-disclaimer"><?= e($s['disclaimer']) ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="wrap">
    <?= section_head($s['what']['eyebrow'], $s['what']['title'], $s['what']['lede']) ?>
    <div class="grid-3">
      <?php foreach ($s['what']['cards'] as [$micro, $title, $text]): ?>
      <article class="info-card"><p class="micro"><?= e($micro) ?></p><h3><?= e($title) ?></h3><p><?= e($text) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap two-col">
    <div>
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($s['cannot']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($s['cannot']['title']) ?></h2>
    </div>
    <div class="prose">
      <ul><?php foreach ($s['cannot']['items'] as $item): ?><li><?= links($item) ?></li><?php endforeach; ?></ul>
      <h2><?= e($s['privacy']['title']) ?></h2>
      <?php foreach ($s['privacy']['paras'] as $para): ?><p><?= links($para) ?></p><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap"><div class="note"><p><?= links($s['note']) ?></p></div></div>
</section>

<section class="section">
  <div class="wrap">
    <div class="cta-band">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($s['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($s['cta']['title']) ?></h2>
      <p class="lede"><?= e($s['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($s['cta']['primary']), url('/standard/'), 'primary') ?>
        <?= btn(e($s['cta']['secondary']), url('/resources/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>

<script type="application/json" id="scan-i18n"><?= json_encode($scanI18n, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
