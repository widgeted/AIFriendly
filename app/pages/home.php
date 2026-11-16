<?php
declare(strict_types=1);
$h = $T['home'];
$faqs = require APP_ROOT . '/data/faqs.php';
$homeFaqs = $faqs['home'];
$faqJson = [];
foreach ($homeFaqs as $item) {
    $faqJson[] = [
        '@type' => 'Question',
        'name' => strip_tags(tr($item['q'])),
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags(tr($item['a'])))],
    ];
}
?>
<section class="hero">
  <div class="wrap hero__grid">
    <div class="hero__head">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $h['hero']['eyebrow'] ?></p>
      <h1 class="display"><span><?= e($h['hero']['title1']) ?></span><span><?= e($h['hero']['title2']) ?></span></h1>
    </div>
    <div class="hero__row">
      <div class="hero__copy">
        <p class="hero__lead"><?= e($h['hero']['lead']) ?></p>
        <p class="hero__body"><?= e($h['hero']['body']) ?></p>
        <div class="actions">
          <?= btn(e(t('common.cta.badge')), url('/badges/'), 'primary') ?>
          <?= btn(e(t('common.cta.standard')), url('/standard/'), 'secondary') ?>
        </div>
        <p class="micro"><?= e($h['hero']['micro']) ?></p>
      </div>

      <div class="hero__visual">
        <div class="hero-mark">
          <?= mark_svg(460, 'hero-mark__svg', 'spectrum') ?>
          <span class="hero-mark__spec"><?= e($h['mark']['spec']) ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="hero__strip">
    <div class="wrap hero__strip__inner">
      <div class="hero__signals">
        <?php foreach ($h['hero']['signal'] as $i => $signal): ?>
        <span><i aria-hidden="true"></i><?= e($signal) ?></span>
        <?php endforeach; ?>
      </div>
      <a class="hero__scroll" href="#idea"><span><?= e($h['hero']['scroll']) ?></span><i aria-hidden="true"></i></a>
    </div>
  </div>
</section>

<section class="section" id="idea">
  <div class="wrap">
    <?= section_head($h['idea']['eyebrow'], $h['idea']['title']) ?>
    <div class="idea-grid">
      <?php foreach ($h['idea']['cells'] as $i => [$num, $title, $text]): ?>
      <article class="idea-cell reveal d<?= $i + 1 ?>">
        <span class="num"><?= e($num) ?></span>
        <h3><?= e($title) ?></h3>
        <p><?= e($text) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="idea-close">
      <p class="pullquote reveal"><?= $h['idea']['quote'] ?></p>
      <div class="idea-close__body reveal d1">
        <p><?= e($h['idea']['close'][0]) ?></p>
        <p><?= e($h['idea']['close'][1]) ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section section--tint" id="ways">
  <div class="wrap">
    <?= section_head($h['ways']['eyebrow'], $h['ways']['title'], $h['ways']['lede']) ?>
    <div class="pillars">
      <?php foreach ($h['ways']['items'] as $i => [$num, $tag, $title, $text, $href, $label]): ?>
      <article class="pillar reveal d<?= $i + 1 ?>">
        <div class="pillar__top"><span class="num"><?= e($num) ?></span><span><?= e($tag) ?></span></div>
        <h3><?= e($title) ?></h3>
        <p><?= e($text) ?></p>
        <a class="text-link" href="<?= e(url($href)) ?>"><span><?= e($label) ?></span><?= arrow_icon() ?></a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="mark">
  <div class="wrap mark-layout">
    <div class="mark-showcase reveal">
      <div class="mark-showcase__mark"><?= mark_svg(420, '', 'spectrum') ?></div>
    </div>
    <div class="mark-copy reveal d1">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $h['mark']['eyebrow'] ?></p>
      <h2 class="h2"><?= $h['mark']['title'] ?></h2>
      <p class="lede"><?= e($h['mark']['p1']) ?></p>
      <p><?= e($h['mark']['p2']) ?></p>
      <p class="mark-spec"><?= e($h['mark']['spec']) ?></p>
      <ul class="mark-points">
        <?php foreach ($h['mark']['points'] as $point): ?>
        <li><?= check_icon() ?><span><?= e($point) ?></span></li>
        <?php endforeach; ?>
      </ul>
      <div class="mark-lockups">
        <img loading="lazy" decoding="async" src="/assets/badges/lockup-ink.svg" width="159" height="44" alt="AI Friendly">
        <img loading="lazy" decoding="async" src="/assets/badges/built-ink.svg" width="250" height="64" alt="Built with AI">
        <img loading="lazy" decoding="async" src="/assets/badges/open-ink.svg" width="250" height="64" alt="Open to AI">
      </div>
      <div class="actions">
        <?= btn(e($h['mark']['cta1']), url('/badges/'), 'primary') ?>
        <?= btn(e($h['mark']['cta2']), url('/license/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>

<section class="section section--tint" id="standard">
  <div class="wrap">
    <div class="section-split">
      <div class="reveal">
        <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $h['standard']['eyebrow'] ?></p>
        <h2 class="h2"><?= $h['standard']['title'] ?></h2>
      </div>
      <div class="reveal d1">
        <p class="lede"><?= e($h['standard']['lede1']) ?></p>
        <p class="lede"><?= e($h['standard']['lede2']) ?></p>
      </div>
    </div>
    <div class="levels">
      <?php foreach ($h['standard']['levels'] as $i => [$id, $name, $sub, $desc, $count]): ?>
      <a class="level-row reveal d<?= $i + 1 ?>" href="<?= e(url('/standard/#level-' . ($i + 1))) ?>">
        <span class="level-row__id"><?= e($id) ?></span>
        <h3><?= e($name) ?><small><?= e($sub) ?></small></h3>
        <p><?= e($desc) ?></p>
        <span class="level-row__count"><strong><?= e($count) ?></strong><?= e(t('common.labels.checks')) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="mini-checks reveal">
      <?php foreach ($h['standard']['mini'] as [$text, $level]): ?>
      <div class="mini-check"><span class="mini-check__tick"><?= check_icon() ?></span><span><?= e($text) ?></span><em><?= e($level) ?></em></div>
      <?php endforeach; ?>
    </div>
    <div class="actions mt-l">
      <?= btn(e($h['standard']['cta1']), url('/standard/'), 'primary') ?>
      <?= btn(e($h['standard']['cta2']), url('/standard/#audit'), 'secondary') ?>
      <a class="text-link" href="<?= e(url('/resources/')) ?>"><span><?= e($h['standard']['cta3']) ?></span><?= arrow_icon() ?></a>
    </div>
  </div>
</section>

<section class="section" id="scan">
  <div class="wrap">
    <div class="scan-panel reveal">
      <div class="scan-panel__copy">
        <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $h['scan']['eyebrow'] ?></p>
        <h2 class="h2"><?= e($h['scan']['title']) ?></h2>
        <p class="lede"><?= e($h['scan']['body']) ?></p>
        <form class="scan-form" action="<?= e(url('/scan/')) ?>" method="get">
          <label class="sr-only" for="home-url"><?= e(t('common.scan.placeholder')) ?></label>
          <input id="home-url" name="url" type="text" inputmode="url" autocomplete="url" placeholder="<?= e(t('common.scan.placeholder')) ?>" required>
          <button class="btn btn--primary" type="submit"><?= e(t('common.scan.button')) ?><?= arrow_icon() ?></button>
        </form>
        <p class="micro"><?= e($h['scan']['micro']) ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section section--tint" id="placements">
  <div class="wrap">
    <div class="section-split">
      <div class="reveal">
        <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $h['placements']['eyebrow'] ?></p>
        <h2 class="h2"><?= e($h['placements']['title']) ?></h2>
      </div>
      <div class="reveal d1">
        <p class="lede"><?= e($h['placements']['lede']) ?></p>
        <div class="actions mt-s"><?= btn(e($h['placements']['cta']), url('/gallery/'), 'secondary') ?></div>
      </div>
    </div>
    <div class="placements">
      <?php
      $mocks = [
          ['site', $h['placements']['items'][0][0], $h['placements']['items'][0][1]],
          ['phone', $h['placements']['items'][1][0], $h['placements']['items'][1][1]],
          ['poster', $h['placements']['items'][2][0], $h['placements']['items'][2][1]],
      ];
      foreach ($mocks as $i => [$type, $label, $context]):
      ?>
      <article class="placement placement--<?= e($type) ?> reveal d<?= $i + 1 ?>">
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
            <div class="mock__screen">
              <span class="mock__notch"></span>
              <img loading="lazy" decoding="async" class="mock__appicon" src="/assets/badges/icon-paper.svg" width="36" height="36" alt="">
              <p class="mock__title">Fieldnotes</p>
              <span class="mock__line"></span><span class="mock__line mock__line--short"></span>
              <img loading="lazy" decoding="async" class="mock__badge" src="/assets/badges/lockup-ink.svg" width="92" height="26" alt="">
            </div>
          </div>
          <?php else: ?>
          <div class="mock mock--poster" aria-hidden="true">
            <img loading="lazy" decoding="async" src="/assets/brand/mark-flat-cream.svg" width="120" height="120" alt="">
            <span>AI FRIENDLY</span>
          </div>
          <?php endif; ?>
        </div>
        <div class="placement__meta"><span><?= e($label) ?></span><span><?= e($context) ?></span></div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="faq">
  <div class="wrap faq-layout">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $h['faq']['eyebrow'] ?></p>
      <h2 class="h2"><?= e($h['faq']['title']) ?></h2>
      <p class="lede"><?= e($h['faq']['lede']) ?></p>
      <a class="text-link mt-s" href="<?= e(url('/about/#faq')) ?>"><span><?= e($h['faq']['cta']) ?></span><?= arrow_icon() ?></a>
    </div>
    <div class="reveal d1"><?= faq_list($homeFaqs) ?></div>
  </div>
</section>

<section class="section section--cta">
  <div class="wrap">
    <div class="cta-band reveal">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($h['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($h['cta']['title']) ?></h2>
      <p class="lede"><?= e($h['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($h['cta']['primary']), url('/badges/'), 'primary') ?>
        <?= btn(e($h['cta']['secondary']), url('/scan/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>

<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'inLanguage' => $langMeta['html'],
    'mainEntity' => $faqJson,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
