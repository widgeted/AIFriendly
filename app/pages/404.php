<?php
declare(strict_types=1);
$n = $T['404'];
?>
<section class="page-hero page-hero--center notfound">
  <div class="wrap page-hero__inner">
    <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($n['eyebrow']) ?></p>
    <h1><?= e($n['title']) ?></h1>
    <p class="lede"><?= e($n['body']) ?></p>
    <div class="actions mt-m justify-center">
      <?= btn(e($n['primary']), url('/'), 'primary') ?>
      <?= btn(e($n['secondary']), url('/standard/'), 'secondary') ?>
    </div>
    <div class="actions mt-m justify-center">
      <a class="text-link" href="<?= e(url('/badges/')) ?>"><span><?= e(t('common.nav.badges')) ?></span><?= arrow_icon() ?></a>
      <a class="text-link" href="<?= e(url('/scan/')) ?>"><span><?= e(t('common.nav.scan')) ?></span><?= arrow_icon() ?></a>
      <a class="text-link" href="/llms.txt"><span>llms.txt</span><?= arrow_icon() ?></a>
    </div>
  </div>
</section>
