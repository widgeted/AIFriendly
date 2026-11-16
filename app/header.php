<?php
declare(strict_types=1);
$navItems = [
    'standard' => ['/standard/', t('common.nav.standard')],
    'badges' => ['/badges/', t('common.nav.badges')],
    'scan' => ['/scan/', t('common.nav.scan')],
    'resources' => ['/resources/', t('common.nav.resources')],
    'about' => ['/about/', t('common.nav.about')],
];
$is404 = $page === '404';
$orgDesc = t('common.org_desc');
$jsonld = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Organization',
            '@id' => SITE_URL . '/#org',
            'name' => 'AI Friendly',
            'url' => SITE_URL . '/',
            'logo' => SITE_URL . '/icon-512.png',
            'description' => $orgDesc,
        ],
        [
            '@type' => 'WebSite',
            '@id' => SITE_URL . '/#site',
            'url' => SITE_URL . '/',
            'name' => 'AI Friendly',
            'description' => $orgDesc,
            'publisher' => ['@id' => SITE_URL . '/#org'],
            'inLanguage' => $langMeta['html'],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $canonical . '#webpage',
            'url' => $canonical,
            'name' => $meta['title'],
            'description' => $meta['desc'],
            'isPartOf' => ['@id' => SITE_URL . '/#site'],
            'inLanguage' => $langMeta['html'],
        ],
    ],
];
?><!doctype html>
<html lang="<?= e($langMeta['html']) ?>" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<script src="/assets/js/boot.js?v=<?= e(ASSET_VERSION) ?>"></script>
<title><?= e($meta['title']) ?></title>
<meta name="description" content="<?= e($meta['desc']) ?>">
<?php if ($is404): ?><meta name="robots" content="noindex"><?php else: ?><link rel="canonical" href="<?= e($canonical) ?>"><?php endif; ?>
<?php if (!$is404): ?>
<link rel="alternate" hreflang="en" href="<?= e(SITE_URL . ($pagePath === '/' ? '/' : $pagePath)) ?>">
<link rel="alternate" hreflang="zh-Hans" href="<?= e(SITE_URL . '/zh' . ($pagePath === '/' ? '/' : $pagePath)) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e(SITE_URL . ($pagePath === '/' ? '/' : $pagePath)) ?>">
<?php endif; ?>
<meta name="theme-color" content="#f8f3ea">
<meta name="color-scheme" content="light">
<meta property="og:type" content="website">
<meta property="og:site_name" content="AI Friendly">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<meta property="og:description" content="<?= e($meta['desc']) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= e(t('common.og_alt')) ?>">
<meta property="og:locale" content="<?= e($langMeta['og']) ?>">
<meta property="og:locale:alternate" content="<?= e(LANGS[$altLang]['og']) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($meta['title']) ?>">
<meta name="twitter:description" content="<?= e($meta['desc']) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="icon" href="/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest?v=6">
<link rel="preload" href="/assets/fonts/ubuntu-400.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/assets/css/site.css?v=<?= e(ASSET_VERSION) ?>">
<script src="/assets/js/site.js?v=<?= e(ASSET_VERSION) ?>" defer></script>
<script type="application/ld+json"><?= json_encode($jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body class="<?= e($bodyClass) ?>">
<a class="skip" href="#main"><?= e(t('common.skip')) ?></a>
<header class="site-header" data-header>
  <div class="wrap header__inner">
    <a class="brand" href="<?= e(url('/')) ?>" aria-label="<?= e(t('common.brand_home')) ?>">
      <span class="brand__mark"><?= mark_svg(30, '', 'spectrum') ?></span>
      <span class="brand__word">AI Friendly</span>
    </a>

    <nav class="site-nav" id="site-nav" data-nav aria-label="<?= e(t('common.primary_nav')) ?>">
      <?php foreach ($navItems as $key => [$href, $label]): ?>
      <a href="<?= e(url($href)) ?>"<?= $page === $key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
      <a class="site-nav__cta btn btn--primary btn--sm" href="<?= e(url('/badges/')) ?>"><?= e(t('common.cta.badge')) ?><?= arrow_icon() ?></a>
      <div class="site-nav__lang">
        <a href="<?= e(switch_url('en')) ?>" hreflang="en" lang="en"<?= $lang === 'en' ? ' aria-current="true"' : '' ?>>EN</a>
        <a href="<?= e(switch_url('zh')) ?>" hreflang="zh-Hans" lang="zh-Hans"<?= $lang === 'zh' ? ' aria-current="true"' : '' ?>>中文</a>
      </div>
    </nav>

    <div class="header__actions">
      <div class="lang-switch" role="group" aria-label="<?= e(t('common.language')) ?>">
        <a href="<?= e(switch_url('en')) ?>" hreflang="en" lang="en"<?= $lang === 'en' ? ' aria-current="true"' : '' ?>>EN</a>
        <span aria-hidden="true">/</span>
        <a href="<?= e(switch_url('zh')) ?>" hreflang="zh-Hans" lang="zh-Hans"<?= $lang === 'zh' ? ' aria-current="true"' : '' ?>>中文</a>
      </div>
      <a class="btn btn--primary btn--sm header__cta" href="<?= e(url('/badges/')) ?>"><?= e(t('common.cta.badge')) ?><?= arrow_icon() ?></a>
      <button class="menu-btn" type="button" aria-expanded="false" aria-controls="site-nav" data-menu>
        <?= menu_icon() ?><span data-menu-label><?= e(t('common.menu')) ?></span>
      </button>
    </div>
  </div>
</header>
<main id="main">
