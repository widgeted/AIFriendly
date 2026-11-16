<?php
declare(strict_types=1);
$footerCols = [
    [
        'title' => t('common.footer.mark_title'),
        'links' => [
            [t('common.footer.badge_kit'), '/badges/'],
            [t('common.footer.guidelines'), '/badges/#guidelines'],
            [t('common.footer.license'), '/license/'],
            [t('common.footer.placements'), '/gallery/'],
        ],
    ],
    [
        'title' => t('common.footer.standard_title'),
        'links' => [
            [t('common.footer.read_standard'), '/standard/'],
            [t('common.footer.self_audit'), '/standard/#audit'],
            [t('common.footer.site_scan'), '/scan/'],
            [t('common.footer.verified'), '/verify/'],
        ],
    ],
    [
        'title' => t('common.footer.project_title'),
        'links' => [
            [t('common.footer.about'), '/about/'],
            [t('common.footer.resources'), '/resources/'],
            [t('common.footer.contact'), '/about/#contact'],
            [t('common.footer.press'), '/about/#press'],
        ],
    ],
    [
        'title' => t('common.footer.machine_title'),
        'links' => [
            ['llms.txt', '/llms.txt'],
            ['sitemap.xml', '/sitemap.xml'],
            ['robots.txt', '/robots.txt'],
            ['openapi.json', '/openapi.json'],
            ['security.txt', '/.well-known/security.txt'],
        ],
    ],
];
?>
</main>
<footer class="site-footer">
  <div class="wrap">
    <div class="footer-top">
      <div class="footer-brand">
        <a class="brand brand--footer" href="<?= e(url('/')) ?>" aria-label="<?= e(t('common.brand_home')) ?>">
          <span class="brand__mark"><?= mark_svg(36, '', 'spectrum') ?></span>
          <span class="brand__word">AI Friendly</span>
        </a>
        <p class="footer-headline"><?= t('common.footer.headline') ?></p>
        <p class="footer-note"><?= t('common.footer.note') ?></p>
        <a class="btn btn--primary" href="<?= e(url('/badges/')) ?>"><?= e(t('common.cta.badge')) ?><?= arrow_icon() ?></a>
      </div>
      <div class="footer-cols">
        <?php foreach ($footerCols as $col): ?>
        <div class="footer-col">
          <h3><?= e($col['title']) ?></h3>
          <?php foreach ($col['links'] as [$label, $href]): ?>
          <?php $rootFile = preg_match('~^/(?:llms\.txt|sitemap\.xml|robots\.txt|openapi\.json|\.well-known/)~', $href); ?>
          <a href="<?= e($rootFile ? $href : url($href)) ?>"><?= e($label) ?></a>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="footer-dogfood">
    <div class="wrap footer-dogfood__inner">
      <p><strong><?= e(t('common.footer.dogfood_label')) ?></strong> <?= e(t('common.footer.dogfood_text')) ?></p>
      <nav aria-label="<?= e(t('common.footer.machine_title')) ?>">
        <a href="/llms.txt">llms.txt</a>
        <a href="/robots.txt">robots.txt</a>
        <a href="/sitemap.xml">sitemap.xml</a>
        <a href="/openapi.json">openapi.json</a>
        <a href="/.well-known/security.txt">security.txt</a>
      </nav>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="wrap footer-bottom__inner">
      <span><?= e(t('common.footer.copyright')) ?></span>
      <span><?= e(t('common.footer.statement')) ?></span>
      <nav aria-label="<?= e(t('common.footer.legal_title')) ?>">
        <a href="<?= e(url('/privacy/')) ?>"><?= e(t('common.footer.privacy')) ?></a>
        <a href="<?= e(url('/terms/')) ?>"><?= e(t('common.footer.terms')) ?></a>
        <a href="<?= e(url('/license/')) ?>"><?= e(t('common.footer.license')) ?></a>
      </nav>
    </div>
  </div>
</footer>
<script type="application/json" id="ui-i18n"><?= json_encode([
    'menu' => t('common.menu'),
    'close' => t('common.close'),
    'copied' => t('common.cta.copied'),
    'copyFailed' => t('common.js.copy_failed'),
    'view' => t('common.cta.view'),
    'hide' => t('common.js.hide'),
    'forms' => [
        'sending' => t('common.forms.sending'),
        'success' => t('common.forms.success'),
        'error' => t('common.forms.error'),
    ],
    'audit' => $T['common']['js']['audit'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

</body>
</html>
