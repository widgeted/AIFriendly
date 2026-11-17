<?php
declare(strict_types=1);
$a = $T['about'];
$faqs = require APP_ROOT . '/data/faqs.php';
$aboutFaqJson = [];
foreach ($faqs['about'] as $item) {
    $aboutFaqJson[] = [
        '@type' => 'Question',
        'name' => strip_tags(tr($item['q'])),
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags(tr($item['a'])))],
    ];
}
?>
<section class="page-hero">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $a['hero']['eyebrow'] ?></p>
    <h1><?= $a['hero']['title'] ?></h1>
    <p class="lede"><?= e($a['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($a['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--flush" id="manifesto">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($a['manifesto']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($a['manifesto']['title']) ?></h2>
    </div>
    <div class="prose reveal d1">
      <?php foreach (array_slice($a['manifesto']['paras'], 0, 3) as $para): ?><p><?= links($para) ?></p><?php endforeach; ?>
      <blockquote><?= e($a['manifesto']['quote']) ?></blockquote>
      <p><?= e($a['manifesto']['paras'][3]) ?></p>
    </div>
  </div>
</section>

<section class="section section--tint" id="principles">
  <div class="wrap">
    <?= section_head($a['principles']['eyebrow'], $a['principles']['title'], $a['principles']['lede']) ?>
    <div class="principle-grid">
      <?php foreach ($a['principles']['items'] as [$num, $title, $text]): ?>
      <article class="principle"><span class="num"><?= e($num) ?></span><h3><?= e($title) ?></h3><p><?= e($text) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="what">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($a['what']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($a['what']['title']) ?></h2>
      <p class="lede"><?= e($a['what']['lede']) ?></p>
    </div>
    <div class="reveal d1">
      <section class="table-scroll" tabindex="0" aria-label="<?= e($a['what']['title']) ?>">
        <table class="compare">
          <thead><tr><th><?= e($a['what']['head'][0]) ?></th><th><?= e($a['what']['head'][1]) ?></th></tr></thead>
          <tbody>
            <?php foreach ($a['what']['rows'] as [$yes, $no]): ?><tr><td><?= e($yes) ?></td><td><?= e($no) ?></td></tr><?php endforeach; ?>
          </tbody>
        </table>
      </section>
      <div class="note mt-m"><p><?= e($a['what']['note']) ?></p></div>
      <p class="lede mt-m"><?= e($a['what']['adjacent']) ?></p>
    </div>
  </div>
</section>

<section class="section section--tint" id="faq">
  <div class="wrap faq-layout">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($a['faq']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($a['faq']['title']) ?></h2>
      <p class="lede"><?= e($a['faq']['lede']) ?></p>
      <a class="text-link mt-s" href="#contact"><span><?= e(t('common.footer.contact')) ?></span><?= arrow_icon() ?></a>
    </div>
    <div class="reveal d1"><?= faq_list($faqs['about']) ?></div>
  </div>
</section>

<section class="section" id="contact">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($a['contact']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($a['contact']['title']) ?></h2>
      <p class="lede"><?= e($a['contact']['lede']) ?></p>
      <div class="note mt-m"><p><?= links($a['contact']['note']) ?></p></div>
    </div>
    <form class="form reveal d1" method="post" action="/api/contact.php" data-form="contact">
      <?= honeypot() ?>
      <input type="hidden" name="type" value="contact">
      <div class="form-row">
        <?= field('name', t('common.forms.name'), 'text', 'autocomplete="name" maxlength="120" required') ?>
        <?= field('email', t('common.forms.email'), 'email', 'autocomplete="email" maxlength="200" required') ?>
      </div>
      <div class="field">
        <label for="c-subject"><?= e(t('common.forms.subject')) ?></label>
        <select id="c-subject" name="subject">
          <option><?= e(t('common.forms.general')) ?></option>
          <option><?= e(t('common.forms.verify')) ?></option>
          <option><?= e(t('common.forms.report')) ?></option>
          <option><?= e(t('common.forms.translation')) ?></option>
          <option><?= e(t('common.forms.press')) ?></option>
          <option><?= e(t('common.forms.bug')) ?></option>
        </select>
      </div>
      <?= field('message', t('common.forms.message'), 'textarea', 'maxlength="4000" required', e(t('common.forms.no_secrets'))) ?>
      <div class="actions">
        <button class="btn btn--primary" type="submit"><?= e(t('common.forms.send')) ?><?= arrow_icon() ?></button>
        <span class="form-msg" data-form-msg aria-live="polite"></span>
      </div>
    </form>
  </div>
</section>

<section class="section section--tint" id="press">
  <div class="wrap two-col two-col--top">
    <div class="reveal">
      <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($a['press']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($a['press']['title']) ?></h2>
      <p class="lede"><?= e($a['press']['lede']) ?></p>
      <div class="actions mt-m">
        <?= btn(e($a['press']['assets']), '/assets/kit/aifriendly-badge-kit.zip', 'secondary', 'download') ?>
        <?= btn(e($a['press']['poster']), '/assets/brand/poster-a3.pdf', 'secondary', 'download') ?>
      </div>
    </div>
    <div class="prose reveal d1">
      <h3><?= e($a['press']['boilerplate_title']) ?></h3>
      <p><?= e($a['press']['boilerplate']) ?></p>
      <h3><?= e($a['press']['facts_title']) ?></h3>
      <ul><?php foreach ($a['press']['facts'] as $fact): ?><li><?= links($fact) ?></li><?php endforeach; ?></ul>
      <h3><?= e($a['press']['colophon_title']) ?></h3>
      <p><?= e($a['press']['colophon']) ?></p>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="cta-band">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($a['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($a['cta']['title']) ?></h2>
      <p class="lede"><?= e($a['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($a['cta']['primary']), url('/badges/'), 'primary') ?>
        <?= btn(e($a['cta']['secondary']), url('/standard/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>

<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    'name' => strip_tags($T['meta']['about']['title']),
    'description' => $T['meta']['about']['desc'],
    'url' => $canonical,
    'inLanguage' => $langMeta['html'],
    'isPartOf' => ['@id' => SITE_URL . '/#site'],
    'about' => ['@id' => SITE_URL . '/#org'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'inLanguage' => $langMeta['html'],
    'mainEntity' => $aboutFaqJson,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
