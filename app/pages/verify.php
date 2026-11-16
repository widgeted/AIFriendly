<?php
declare(strict_types=1);
$v = $T['verify'];
$levelOptions = $T['standard']['audit']['levels'];
?>
<section class="page-hero">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($v['hero']['eyebrow']) ?></p>
    <h1><?= $v['hero']['title'] ?></h1>
    <p class="lede"><?= e($v['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($v['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
    <div class="actions mt-m">
      <?= btn(e($v['hero']['actions'][0]), '#apply', 'primary') ?>
      <?= btn(e($v['hero']['actions'][1]), '#criteria', 'secondary') ?>
    </div>
  </div>
</section>

<section class="section section--flush" id="compare">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($v['compare']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($v['compare']['title']) ?></h2>
      <p class="lede"><?= e($v['compare']['lede']) ?></p>
      <div class="note mt-m"><p><?= links($v['compare']['note']) ?></p></div>
    </div>
    <div class="reveal d1">
      <div class="table-scroll" tabindex="0" role="region" aria-label="<?= e($v['compare']['title']) ?>">
        <table class="compare">
          <thead><tr><?php foreach ($v['compare']['head'] as $h): ?><th><?= e($h) ?></th><?php endforeach; ?></tr></thead>
          <tbody>
            <?php foreach ($v['compare']['rows'] as [$label, $a, $b]): ?><tr><td><?= e($label) ?></td><td><?= e($a) ?></td><td><?= e($b) ?></td></tr><?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<section class="section section--tint" id="criteria">
  <div class="wrap">
    <?= section_head($v['criteria']['eyebrow'], $v['criteria']['title'], $v['criteria']['lede']) ?>
    <div class="grid-2">
      <?php foreach ($v['criteria']['items'] as [$micro, $title, $text]): ?>
      <article class="info-card"><p class="micro"><?= e($micro) ?></p><h3><?= e($title) ?></h3><p><?= e($text) ?></p></article>
      <?php endforeach; ?>
    </div>
    <div class="note mt-l"><p><?= links($v['criteria']['note']) ?></p></div>
  </div>
</section>

<section class="section" id="process">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($v['process']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($v['process']['title']) ?></h2>
      <p class="lede"><?= e($v['process']['lede']) ?></p>
    </div>
    <div class="steps reveal d1">
      <?php foreach ($v['process']['steps'] as [$title, $text]): ?>
      <div class="step"><div><h3><?= e($title) ?></h3><p><?= e($text) ?></p></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tight">
  <div class="wrap"><div class="note"><p><?= links($v['pricing']) ?></p></div></div>
</section>

<section class="section section--tint" id="apply">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($v['apply']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($v['apply']['title']) ?></h2>
      <p class="lede"><?= e($v['apply']['lede']) ?></p>
      <div class="note mt-m"><p><?= links($v['apply']['note']) ?></p></div>
      <a class="text-link mt-m" href="<?= e(url('/standard/#audit')) ?>"><span><?= e($v['apply']['audit']) ?></span><?= arrow_icon() ?></a>
    </div>
    <form class="form reveal d1" method="post" action="/api/contact.php" data-form="verify">
      <?= honeypot() ?>
      <input type="hidden" name="type" value="verify">
      <div class="form-row">
        <?= field('name', t('common.forms.name'), 'text', 'autocomplete="name" maxlength="120" required') ?>
        <?= field('email', t('common.forms.email'), 'email', 'autocomplete="email" maxlength="200" required') ?>
      </div>
      <?= field('url', t('common.forms.service'), 'url', 'inputmode="url" placeholder="https://" maxlength="300" required') ?>
      <div class="field">
        <label for="v-level"><?= e(t('common.forms.level')) ?></label>
        <select id="v-level" name="level">
          <?php foreach ($levelOptions as $option): ?><option><?= e($option) ?></option><?php endforeach; ?>
        </select>
      </div>
      <?= field('evidence', t('common.forms.evidence'), 'textarea', 'maxlength="4000" required', e(t('common.forms.no_credentials'))) ?>
      <div class="actions">
        <button class="btn btn--primary" type="submit"><?= e(t('common.forms.send')) ?><?= arrow_icon() ?></button>
        <span class="form-msg" data-form-msg aria-live="polite"></span>
      </div>
    </form>
  </div>
</section>

<section class="section section--cta">
  <div class="wrap">
    <div class="cta-band">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($v['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($v['cta']['title']) ?></h2>
      <p class="lede"><?= e($v['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($v['cta']['primary']), url('/badges/'), 'primary') ?>
        <?= btn(e($v['cta']['secondary']), url('/scan/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>
