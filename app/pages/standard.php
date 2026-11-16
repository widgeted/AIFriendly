<?php
declare(strict_types=1);
$s = $T['standard'];
$checks = require APP_ROOT . '/data/checks.php';
$standardFile = $lang === 'zh' ? '/assets/kit/standard-zh.md' : '/assets/kit/standard.md';
$sources = [
    ['RFC 9309 — Robots Exclusion Protocol', 'https://www.rfc-editor.org/rfc/rfc9309'],
    ['sitemaps.org — XML sitemap protocol', 'https://www.sitemaps.org/protocol.html'],
    ['schema.org — structured data vocabulary', 'https://schema.org/'],
    ['OpenAPI Specification 3.1', 'https://spec.openapis.org/oas/v3.1.0'],
    ['RFC 9457 — Problem Details for HTTP APIs', 'https://www.rfc-editor.org/rfc/rfc9457'],
    ['RFC 9116 — security.txt', 'https://www.rfc-editor.org/rfc/rfc9116'],
    ['WCAG 2.2', 'https://www.w3.org/TR/WCAG22/'],
    ['llms.txt — an emerging proposal', 'https://llmstxt.org/'],
    ['Model Context Protocol (MCP)', 'https://modelcontextprotocol.io/'],
    ['C2PA — content provenance', 'https://c2pa.org/'],
    ['Community list of AI crawler user agents', 'https://github.com/ai-robots-txt/ai.robots.txt'],
];
$groups = [1 => [], 2 => [], 3 => []];
foreach ($checks as $c) {
    $groups[(int) $c['level']][] = $c;
}
?>
<section class="page-hero">
  <div class="wrap page-hero__inner">
    <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $s['hero']['eyebrow'] ?></p>
    <h1><?= $s['hero']['title'] ?></h1>
    <p class="lede"><?= e($s['hero']['lede']) ?></p>
    <div class="page-meta">
      <?php foreach ($s['hero']['meta'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?>
    </div>
    <div class="actions mt-m">
      <?= btn(e($s['hero']['actions'][0]), '#audit', 'primary') ?>
      <?= btn(e($s['hero']['actions'][1]), $standardFile, 'secondary', 'download') ?>
      <?= btn(e($s['hero']['actions'][2]), url('/scan/'), 'secondary') ?>
    </div>
  </div>
</section>

<section class="section section--flush">
  <div class="wrap side-layout">
    <aside class="side-toc" aria-label="<?= e($s['toc']['title']) ?>">
      <h2><?= e($s['toc']['title']) ?></h2>
      <a href="#principles"><?= e($s['toc']['principles']) ?><span>01</span></a>
      <a href="#level-1"><?= e($s['toc']['l1']) ?><span>10</span></a>
      <a href="#level-2"><?= e($s['toc']['l2']) ?><span>07</span></a>
      <a href="#level-3"><?= e($s['toc']['l3']) ?><span>07</span></a>
      <a href="#audit"><?= e($s['toc']['audit']) ?><span>24</span></a>
      <a href="#claim"><?= e($s['toc']['claim']) ?></a>
      <a href="#sources"><?= e($s['toc']['sources']) ?></a>
      <a class="btn btn--secondary btn--sm btn--block" href="<?= e($standardFile) ?>" download><?= e($s['toc']['download']) ?></a>
    </aside>

    <div class="standard-body">
      <section id="principles" class="anchor">
        <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $s['intro']['eyebrow'] ?></p>
        <h2 class="h2"><?= e($s['intro']['title']) ?></h2>
        <div class="prose mt-m">
          <?php foreach ($s['intro']['paras'] as $para): ?><p><?= links($para) ?></p><?php endforeach; ?>
        </div>
        <div class="principle-grid mt-l">
          <?php foreach ($s['intro']['principles'] as [$num, $title, $text]): ?>
          <article class="principle">
            <span class="num"><?= e($num) ?></span>
            <h3><?= e($title) ?></h3>
            <p><?= e($text) ?></p>
          </article>
          <?php endforeach; ?>
        </div>
        <div class="note mt-l">
          <?php foreach ($s['intro']['note'] as $para): ?><p><?= links($para) ?></p><?php endforeach; ?>
        </div>
      </section>

      <?php foreach ([1, 2, 3] as $level): ?>
      <section class="level-block anchor" id="level-<?= $level ?>">
        <div class="level-head">
          <span class="level-head__id">L<?= $level ?></span>
          <h2><?= e($s['level_' . $level . '_name']) ?></h2>
          <p><?= count($groups[$level]) ?> <?= e(t('common.labels.checks')) ?></p>
        </div>
        <p class="level-intro"><?= e($s['level_intro'][$level]) ?></p>
        <?php foreach ($groups[$level] as $check): ?>
        <?= check_item($check, $s['audit_label']) ?>
<?php endforeach; ?>
      </section>
<?php endforeach; ?>

      <section id="claim" class="anchor">
        <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= $s['claim']['eyebrow'] ?></p>
        <h2 class="h2"><?= $s['claim']['title'] ?></h2>
        <div class="grid-2 mt-m">
          <?php foreach ($s['claim']['cards'] as [$micro, $title, $text, $label, $href]): ?>
          <article class="info-card">
            <p class="micro"><?= e($micro) ?></p>
            <h3><?= e($title) ?></h3>
            <p><?= e($text) ?></p>
            <a class="text-link" href="<?= e(url($href)) ?>"><span><?= e($label) ?></span><?= arrow_icon() ?></a>
          </article>
          <?php endforeach; ?>
        </div>
        <div class="note mt-m"><p><?= links($s['claim']['note']) ?></p></div>
      </section>

      <section id="sources" class="anchor">
        <p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span><?= e($s['sources']['eyebrow']) ?></p>
        <h2 class="h2"><?= e($s['sources']['title']) ?></h2>
        <p class="prose mt-m"><?= e($s['sources']['intro']) ?></p>
        <ul class="source-list">
          <?php foreach ($sources as [$name, $href]): ?>
          <li><a href="<?= e($href) ?>" rel="noopener"><?= e($name) ?><?= arrow_icon() ?></a></li>
          <?php endforeach; ?>
        </ul>
      </section>
    </div>
  </div>
</section>

<section class="section" id="audit">
  <div class="wrap">
    <?= section_head($s['audit']['eyebrow'], $s['audit']['title'], $s['audit']['lede']) ?>
    <noscript><p class="note"><?= links($s['audit']['noscript']) ?></p></noscript>
    <div class="audit" data-audit>
      <div class="audit-list"><?= audit_list($checks) ?></div>
      <aside class="audit-score" aria-label="<?= e($s['audit']['saved']) ?>">
        <p class="micro"><?= e($s['audit']['saved']) ?></p>
        <div class="audit-score__num"><span data-score>0</span><small>/ 24</small></div>
        <div class="audit-bar"><i data-bar></i></div>
        <div class="audit-levels">
          <?php foreach ([1, 2, 3] as $level): ?>
          <div class="audit-level" data-level="l<?= $level ?>"><span><?= e($s['audit']['levels'][$level - 1]) ?></span><i class="audit-level__dot"></i></div>
          <?php endforeach; ?>
        </div>
        <div class="actions audit-actions">
          <button class="btn btn--primary btn--sm" type="button" data-audit-copy><?= e($s['audit']['copy']) ?></button>
          <button class="btn btn--secondary btn--sm" type="button" data-audit-download><?= e($s['audit']['download']) ?></button>
          <button class="btn btn--ghost btn--sm" type="button" data-audit-reset><?= e($s['audit']['reset']) ?></button>
        </div>
        <p class="audit-saved" data-audit-status aria-live="polite"><?= e($s['audit']['status']) ?></p>
      </aside>
    </div>
    <div class="note mt-l"><p><?= links($s['audit']['note']) ?></p></div>
  </div>
</section>

<section class="section section--cta">
  <div class="wrap">
    <div class="cta-band">
      <p class="eyebrow justify-center"><span class="spectrum-dot" aria-hidden="true"></span><?= e($s['cta']['eyebrow']) ?></p>
      <h2 class="h2"><?= e($s['cta']['title']) ?></h2>
      <p class="lede"><?= e($s['cta']['body']) ?></p>
      <div class="actions justify-center">
        <?= btn(e($s['cta']['primary']), url('/scan/'), 'primary') ?>
        <?= btn(e($s['cta']['secondary']), url('/resources/'), 'secondary') ?>
      </div>
    </div>
  </div>
</section>

<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'TechArticle',
    'headline' => strip_tags($T['meta']['standard']['title']),
    'description' => $T['meta']['standard']['desc'],
    'datePublished' => '2026-11-15',
    'dateModified' => '2026-11-15',
    'inLanguage' => $langMeta['html'],
    'version' => '0.1',
    'license' => 'https://creativecommons.org/licenses/by/4.0/',
    'author' => ['@type' => 'Organization', 'name' => 'AI Friendly', 'url' => SITE_URL . '/'],
    'publisher' => ['@type' => 'Organization', 'name' => 'AI Friendly', 'url' => SITE_URL . '/'],
    'mainEntityOfPage' => $canonical,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
