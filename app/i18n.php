<?php
declare(strict_types=1);

function load_lang(string $lang): array
{
    $file = APP_ROOT . '/lang/' . $lang . '.php';
    if (!is_file($file)) {
        throw new RuntimeException('Missing language file: ' . $lang);
    }
    $data = require $file;
    if (!is_array($data)) {
        throw new RuntimeException('Invalid language file: ' . $lang);
    }
    $legalFile = APP_ROOT . '/lang/' . $lang . '-legal.php';
    if (is_file($legalFile)) {
        $legal = require $legalFile;
        if (is_array($legal)) {
            $data = array_replace_recursive($data, $legal);
        }
    }
    $GLOBALS['T'] = $data;
    return $data;
}

/** Dot-path translation lookup. Returns a string (or the fallback). */
function t(string $key, string $fallback = ''): string
{
    $ref = $GLOBALS['T'] ?? [];
    foreach (explode('.', $key) as $part) {
        if (!is_array($ref) || !array_key_exists($part, $ref)) {
            return $fallback !== '' ? $fallback : $key;
        }
        $ref = $ref[$part];
    }
    return is_scalar($ref) ? (string) $ref : $fallback;
}

/** Pick the current language from a ['en'=>..., 'zh'=>...] pair. */
function tr($pair, string $fallback = ''): string
{
    $lang = $GLOBALS['lang'] ?? 'en';
    if (is_array($pair)) {
        return (string) ($pair[$lang] ?? $pair['en'] ?? $fallback);
    }
    return is_scalar($pair) ? (string) $pair : $fallback;
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function links(string $html): string
{
    $map = [
        '{badges}' => url('/badges/'),
        '{standard}' => url('/standard/'),
        '{audit}' => url('/standard/#audit'),
        '{scan}' => url('/scan/'),
        '{verify}' => url('/verify/'),
        '{resources}' => url('/resources/'),
        '{about}' => url('/about/'),
        '{gallery}' => url('/gallery/'),
        '{license}' => url('/license/'),
        '{privacy}' => url('/privacy/'),
        '{terms}' => url('/terms/'),
        '{contact}' => url('/about/#contact'),
        '{llms}' => '/llms.txt',
        '{openapi}' => '/openapi.json',
        '{robots}' => '/robots.txt',
        '{sitemap}' => '/sitemap.xml',
        '{standard_md}' => ($GLOBALS['lang'] ?? 'en') === 'zh' ? '/assets/kit/standard-zh.md' : '/assets/kit/standard.md',
    ];
    return str_replace(array_keys($map), array_values($map), $html);
}

function url(string $path = '/'): string
{
    if (preg_match('~^(https?:|mailto:|#)~i', $path)) {
        return $path;
    }
    $prefix = ($GLOBALS['lang'] ?? 'en') === 'zh' ? '/zh' : '';
    if ($path === '/') {
        return $prefix . '/';
    }
    return $prefix . '/' . ltrim($path, '/');
}

function lang_url(string $lang, string $path = '/'): string
{
    if ($path === '/') {
        return $lang === 'zh' ? '/zh/' : '/';
    }
    return ($lang === 'zh' ? '/zh' : '') . '/' . ltrim($path, '/');
}

function switch_url(string $targetLang): string
{
    $pagePath = $GLOBALS['pagePath'] ?? '/';
    if ($pagePath === null) {
        return $targetLang === 'zh' ? '/zh/' : '/';
    }
    $query = '';
    if (($GLOBALS['page'] ?? '') === 'scan' && isset($_GET['url']) && is_string($_GET['url'])) {
        $query = '?url=' . rawurlencode($_GET['url']);
    }
    return lang_url($targetLang, $pagePath) . $query;
}

function is_zh(): bool
{
    return ($GLOBALS['lang'] ?? 'en') === 'zh';
}
