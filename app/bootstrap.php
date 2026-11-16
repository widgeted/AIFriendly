<?php
declare(strict_types=1);

/**
 * AI Friendly — request bootstrap and routing.
 * Light, server-rendered, bilingual (EN / 简体中文).
 */

const SITE_URL = 'https://aifriendly.fyi';
const APP_ROOT = __DIR__;
const SITE_ROOT = __DIR__ . '/..';
const LANGS = [
    'en' => ['html' => 'en', 'label' => 'EN', 'name' => 'English', 'og' => 'en_US'],
    'zh' => ['html' => 'zh-Hans', 'label' => '中文', 'name' => '简体中文', 'og' => 'zh_CN'],
];

const ROUTES = [
    'home' => '/',
    'standard' => '/standard/',
    'badges' => '/badges/',
    'scan' => '/scan/',
    'resources' => '/resources/',
    'about' => '/about/',
    'verify' => '/verify/',
    'gallery' => '/gallery/',
    'license' => '/license/',
    'privacy' => '/privacy/',
    'terms' => '/terms/',
];

ini_set('display_errors', '0');
ini_set('log_errors', '1');

$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$originalPath = rawurldecode((string) parse_url($requestUri, PHP_URL_PATH));
$hadTrailingSlash = $originalPath === '/' || str_ends_with($originalPath, '/');
$pathOnly = '/' . trim((string) preg_replace('#/+#', '/', $originalPath), '/');
if ($pathOnly === '//' || $pathOnly === '') {
    $pathOnly = '/';
}
if ($pathOnly !== '/') {
    $pathOnly .= '/';
}
$query = (string) ($_SERVER['QUERY_STRING'] ?? '');

if ($originalPath === '/index.php') {
    header('Location: /' . ($query !== '' ? '?' . $query : ''), true, 301);
    exit;
}

// Prefixless English is canonical. /en/... redirects to the unprefixed path.
if ($pathOnly === '/en/' || str_starts_with($pathOnly, '/en/')) {
    $target = substr($pathOnly, 3) ?: '/';
    header('Location: ' . $target . ($query !== '' ? '?' . $query : ''), true, 301);
    exit;
}
if ($pathOnly === '/zh') {
    header('Location: /zh/' . ($query !== '' ? '?' . $query : ''), true, 301);
    exit;
}

$lang = 'en';
$path = $pathOnly;
if ($pathOnly === '/zh/' || str_starts_with($pathOnly, '/zh/')) {
    $lang = 'zh';
    $path = substr($pathOnly, 3) ?: '/';
}

$slug = $path === '/' ? 'home' : trim($path, '/');
if (!array_key_exists($slug, ROUTES)) {
    http_response_code(404);
    $slug = '404';
}
$page = $slug;
$pagePath = $slug === '404' ? null : ROUTES[$slug];

// Add a trailing slash to page-like URLs, preserving the query string.
if (!$hadTrailingSlash && $page !== '404' && $originalPath !== '/') {
    header('Location: ' . $pathOnly . ($query !== '' ? '?' . $query : ''), true, 301);
    exit;
}

$cssFile = SITE_ROOT . '/assets/css/site.css';
$jsFile = SITE_ROOT . '/assets/js/site.js';
$bootFile = SITE_ROOT . '/assets/js/boot.js';
$assetSeed = (is_file($cssFile) ? filemtime($cssFile) : 1) . ':' . (is_file($jsFile) ? filemtime($jsFile) : 1) . ':' . (is_file($bootFile) ? filemtime($bootFile) : 1);
define('ASSET_VERSION', substr(hash('sha256', $assetSeed), 0, 8));

$mediaFiles = [
    SITE_ROOT . '/assets/badges/lockup-ink.svg',
    SITE_ROOT . '/assets/brand/mark.svg',
    SITE_ROOT . '/assets/kit/aifriendly-badge-kit.zip',
    SITE_ROOT . '/og-en.png',
    SITE_ROOT . '/favicon.svg',
];
$mediaSeed = '';
foreach ($mediaFiles as $mediaFile) {
    $mediaSeed .= (is_file($mediaFile) ? filemtime($mediaFile) : 1) . ':';
}
define('MEDIA_VERSION', substr(hash('sha256', $mediaSeed), 0, 8));

require APP_ROOT . '/i18n.php';
require APP_ROOT . '/ui.php';
ob_start('version_assets');

$T = load_lang($lang);
$langMeta = LANGS[$lang];
$meta = $T['meta'][$page] ?? $T['meta']['404'];
$canonicalPath = $pagePath ?? '/' . ltrim($path, '/');
$canonical = SITE_URL . ($lang === 'zh' ? '/zh' . ($canonicalPath === '/' ? '/' : $canonicalPath) : $canonicalPath);
$altLang = $lang === 'en' ? 'zh' : 'en';
$altPath = $pagePath ?? '/';
$altUrl = SITE_URL . ($altLang === 'zh' ? '/zh' . ($altPath === '/' ? '/' : $altPath) : $altPath);
$ogImage = SITE_URL . '/og-' . $lang . '.png';
$bodyClass = 'page-' . $page . ' lang-' . $lang;
