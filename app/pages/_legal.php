<?php
declare(strict_types=1);
$legal = $T[$page];
?>
<section class="page-hero">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($legal['hero']['eyebrow']) ?></p>
    <h1><?= $legal['hero']['title'] ?></h1>
    <p class="lede"><?= e($legal['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($legal['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--flush">
  <div class="wrap side-layout">
    <aside class="side-toc" aria-label="<?= e($legal['hero']['title']) ?>">
      <h2><?= e(t('standard.toc.title')) ?></h2>
      <?php foreach ($legal['toc'] as $i => [$id, $label]): ?>
      <a href="#<?= e($id) ?>"><?= e($label) ?><span><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span></a>
      <?php endforeach; ?>
    </aside>
    <div class="standard-body legal-body">
      <?php foreach ($legal['sections'] as $section): ?>
      <section id="<?= e($section['id']) ?>" class="anchor">
        <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($section['eyebrow']) ?></p>
        <h2 class="h2"><?= e($section['title']) ?></h2>
        <div class="prose mt-m"><?= links($section['html']) ?></div>
      </section>
      <?php endforeach; ?>
    </div>
  </div>
</section>
