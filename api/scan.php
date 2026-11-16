<?php
declare(strict_types=1);

require __DIR__ . '/lib.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    header('Allow: GET');
    fail(405, 'Use GET.');
}

set_time_limit(30);
if (!rate_limit('scan', SCAN_LIMIT)) {
    fail(429, 'Too many scans from this connection. Try again in a little while.');
}

$uiLang = (($_GET['lang'] ?? 'en') === 'zh') ? 'zh' : 'en';
$raw = trim((string) ($_GET['url'] ?? ''));
if ($raw === '') {
    fail(400, 'Add a URL to scan.');
}
if (preg_match('#^[a-z][a-z0-9+.-]*://#i', $raw) && !preg_match('#^https?://#i', $raw)) {
    fail(400, 'Only http and https URLs can be scanned.');
}
if (!preg_match('#^https?://#i', $raw)) {
    $raw = 'https://' . $raw;
}

try {
    $info = validate_url($raw);
} catch (Throwable $e) {
    fail(400, $e->getMessage());
}

$key = hash('sha256', $info['url'] . '|' . $uiLang);
$cacheFile = data_path('cache') . '/' . $key . '.json';
if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < CACHE_TTL) {
    $cached = json_decode((string) file_get_contents($cacheFile), true);
    if (is_array($cached) && isset($cached['checks'])) {
        $cached['ok'] = true;
        $cached['url'] = $info['url'];
        $cached['final_url'] = $info['url'];
        $cached['cached'] = true;
        json_out(200, $cached);
    }
}

$page = http_get($info['url'], 7, 3, MAX_BODY);
if ($page['status'] === 0) {
    fail(422, 'We could not reach that site. Check the address and try again.');
}
if ($page['status'] >= 400) {
    fail(422, 'The site returned HTTP ' . $page['status'] . '. The scanner only reads public pages.');
}
$html = $page['body'];
$finalUrl = $page['url'];
$ctype = strtolower($page['headers']['content-type'] ?? '');
if ($ctype !== '' && !str_contains($ctype, 'html') && !str_contains($ctype, 'text/plain')) {
    fail(422, 'That URL did not return an HTML page.');
}
if (strlen(trim($html)) < 120) {
    fail(422, 'The page returned too little HTML to scan.');
}

$dom = new DOMDocument();
$prev = libxml_use_internal_errors(true);
@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
libxml_clear_errors();
libxml_use_internal_errors($prev);
$xp = new DOMXPath($dom);

$checks = [];

/* ---------- read the head ---------- */
$title = trim((string) $xp->evaluate('string(//title[1])'));
$titleLen = mb_strlen($title);

$desc = '';
$viewport = '';
$lang = mb_substr(trim((string) $xp->evaluate('string(//html[1]/@lang)')), 0, 20);
foreach ($xp->query('//meta[@name]') as $meta) {
    $name = strtolower(trim($meta->getAttribute('name')));
    if ($name === 'description') {
        $desc = trim($meta->getAttribute('content'));
    } elseif ($name === 'viewport') {
        $viewport = trim($meta->getAttribute('content'));
    }
}

$canonical = '';
foreach ($xp->query('//link[@rel]') as $link) {
    $rel = strtolower($link->getAttribute('rel'));
    if (str_contains($rel, 'canonical')) {
        $canonical = trim($link->getAttribute('href'));
        break;
    }
}

$og = ['title' => '', 'description' => '', 'image' => '', 'url' => ''];
foreach ($xp->query('//meta[@property]') as $meta) {
    $prop = strtolower(trim($meta->getAttribute('property')));
    if (str_starts_with($prop, 'og:')) {
        $key = substr($prop, 3);
        if (array_key_exists($key, $og)) {
            $og[$key] = trim($meta->getAttribute('content'));
        }
    }
}
$ogCount = count(array_filter($og, fn($v) => $v !== ''));

$h1Count = $xp->query('//h1')->length;
$mainText = main_text_len($html);

$ldTypes = [];
$ldBlocks = 0;
$ldInvalid = 0;
foreach ($xp->query('//script[@type="application/ld+json"]') as $script) {
    $ldBlocks++;
    $decoded = json_decode(trim($script->textContent), true);
    if (!is_array($decoded)) {
        $ldInvalid++;
        continue;
    }
    $nodes = $decoded;
    if (isset($decoded['@graph']) && is_array($decoded['@graph'])) {
        $nodes = $decoded['@graph'];
    }
    foreach ((array) $nodes as $node) {
        if (is_array($node) && isset($node['@type'])) {
            foreach ((array) $node['@type'] as $t) {
                if (is_string($t) && $t !== '' && !in_array($t, $ldTypes, true)) {
                    $ldTypes[] = mb_substr($t, 0, 60);
                }
            }
        }
    }
}

$imgCount = 0;
$imgMissing = 0;
foreach ($xp->query('//img') as $img) {
    $imgCount++;
    if (!$img->hasAttribute('alt')) {
        $imgMissing++;
    }
}

/* ---------- build read checks ---------- */
if ($titleLen === 0) {
    $checks[] = check('title', 'read', 'Page title', 6, 'fail', 'No <title> element was found.', 'Add a unique, descriptive title of 10–70 characters.');
} elseif ($titleLen >= 10 && $titleLen <= 70) {
    $checks[] = check('title', 'read', 'Page title', 6, 'pass', 'A title is present (' . $titleLen . ' characters).');
} else {
    $checks[] = check('title', 'read', 'Page title', 6, 'warn', 'A title is present but is ' . $titleLen . ' characters long.', 'Aim for 10–70 characters that identify the page without the URL.');
}

$descLen = mb_strlen($desc);
if ($descLen === 0) {
    $checks[] = check('meta_description', 'read', 'Meta description', 5, 'fail', 'No meta description was found.', 'Add a 50–160 character summary of the page.');
} elseif ($descLen >= 50 && $descLen <= 160) {
    $checks[] = check('meta_description', 'read', 'Meta description', 5, 'pass', 'A meta description is present (' . $descLen . ' characters).');
} else {
    $checks[] = check('meta_description', 'read', 'Meta description', 5, 'warn', 'A meta description is present but is ' . $descLen . ' characters long.', 'Aim for 50–160 characters.');
}

if ($canonical === '') {
    $checks[] = check('canonical', 'read', 'Canonical URL', 5, 'fail', 'No canonical link was found.', 'Add <link rel="canonical"> pointing at the preferred URL.');
} elseif (preg_match('#^https?://#i', $canonical)) {
    $checks[] = check('canonical', 'read', 'Canonical URL', 5, 'pass', 'An absolute canonical URL is declared.');
} else {
    $checks[] = check('canonical', 'read', 'Canonical URL', 5, 'warn', 'The canonical link is relative.', 'Use an absolute URL in the canonical link.');
}

if ($lang !== '') {
    $checks[] = check('lang', 'read', 'Page language', 3, 'pass', 'The page declares a language (' . htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') . ').');
} else {
    $checks[] = check('lang', 'read', 'Page language', 3, 'fail', 'The <html> element has no lang attribute.', 'Add lang="en" (or the correct language) to <html>.');
}

if ($h1Count === 1) {
    $checks[] = check('h1', 'read', 'Heading structure', 4, 'pass', 'Exactly one H1 was found.');
} elseif ($h1Count === 0) {
    $checks[] = check('h1', 'read', 'Heading structure', 4, 'fail', 'No H1 was found.', 'Add one clear H1 that describes the page.');
} else {
    $checks[] = check('h1', 'read', 'Heading structure', 4, 'warn', $h1Count . ' H1 elements were found.', 'Use one H1 per page; use H2–H6 for sections.');
}

if ($mainText >= 600) {
    $checks[] = check('ssr_content', 'read', 'Server-rendered content', 10, 'pass', 'About ' . $mainText . ' characters of readable text are present in the HTML response.');
} elseif ($mainText >= 200) {
    $checks[] = check('ssr_content', 'read', 'Server-rendered content', 10, 'warn', 'Only about ' . $mainText . ' characters of readable text were found.', 'Add the main copy to the server-rendered HTML; do not rely on client-side rendering.');
} else {
    $checks[] = check('ssr_content', 'read', 'Server-rendered content', 10, 'fail', 'Almost no readable text was found in the HTML response.', 'Render the main content server-side, or add static generation or prerendering.');
}

if ($imgCount === 0) {
    $checks[] = check('image_alt', 'read', 'Image alternatives', 8, 'pass', 'No <img> elements were found on this page.');
} elseif ($imgMissing === 0) {
    $checks[] = check('image_alt', 'read', 'Image alternatives', 8, 'pass', 'All ' . $imgCount . ' images have an alt attribute.');
} elseif ($imgMissing / $imgCount < 0.5) {
    $checks[] = check('image_alt', 'read', 'Image alternatives', 8, 'warn', $imgMissing . ' of ' . $imgCount . ' images have no alt attribute.', 'Add useful alt text, or alt="" for decorative images.');
} else {
    $checks[] = check('image_alt', 'read', 'Image alternatives', 8, 'fail', $imgMissing . ' of ' . $imgCount . ' images have no alt attribute.', 'Add useful alt text to meaningful images; use alt="" for decoration.');
}

if ($viewport !== '' && stripos($viewport, 'width=device-width') !== false) {
    $checks[] = check('viewport', 'read', 'Mobile viewport', 3, 'pass', 'A responsive viewport is declared.');
} elseif ($viewport !== '') {
    $checks[] = check('viewport', 'read', 'Mobile viewport', 3, 'warn', 'A viewport is declared but does not set width=device-width.', 'Use width=device-width, initial-scale=1.');
} else {
    $checks[] = check('viewport', 'read', 'Mobile viewport', 3, 'fail', 'No viewport meta tag was found.', 'Add <meta name="viewport" content="width=device-width, initial-scale=1">.');
}

/* ---------- discover checks ---------- */
if ($ldBlocks > 0 && count($ldTypes) > 0) {
    $types = implode(', ', array_slice($ldTypes, 0, 6));
    $checks[] = check('structured_data', 'discover', 'Structured data', 10, 'pass', 'Valid JSON-LD found (' . $ldBlocks . ' block' . ($ldBlocks === 1 ? '' : 's') . '; types: ' . $types . ').');
} elseif ($ldBlocks > 0) {
    $checks[] = check('structured_data', 'discover', 'Structured data', 10, 'warn', 'JSON-LD blocks were found but none contained a valid @type.', 'Fix the JSON syntax and add an @type such as Organization or WebSite.');
} else {
    $checks[] = check('structured_data', 'discover', 'Structured data', 10, 'fail', 'No JSON-LD structured data was found.', 'Add schema.org Organization and WebSite markup in the page head.');
}

if ($ogCount >= 3) {
    $checks[] = check('open_graph', 'discover', 'Open Graph metadata', 6, 'pass', $ogCount . ' of 4 core Open Graph fields are present.');
} elseif ($ogCount > 0) {
    $checks[] = check('open_graph', 'discover', 'Open Graph metadata', 6, 'warn', 'Only ' . $ogCount . ' of 4 core Open Graph fields are present.', 'Add og:title, og:description, og:image and og:url.');
} else {
    $checks[] = check('open_graph', 'discover', 'Open Graph metadata', 6, 'fail', 'No Open Graph metadata was found.', 'Add og:title, og:description, og:image and og:url.');
}

$finalParts = parse_url($finalUrl) ?: [];
$fScheme = strtolower((string) ($finalParts['scheme'] ?? $info['scheme']));
$fHost = (string) ($finalParts['host'] ?? $info['host']);
$fPort = (int) ($finalParts['port'] ?? ($fScheme === 'https' ? 443 : 80));
$origin = $fScheme . '://' . $fHost . (($fPort !== ($fScheme === 'https' ? 443 : 80)) ? ':' . $fPort : '');
$robotsUrl = $origin . '/robots.txt';
$robots = http_get($robotsUrl, 4, 2, 200000);
$robotsBody = $robots['body'];
$robotsValid = $robots['status'] === 200
    && trim($robotsBody) !== ''
    && stripos($robotsBody, 'user-agent') !== false
    && !preg_match('#<html#i', $robotsBody);

if ($robotsValid) {
    $checks[] = check('robots_txt', 'discover', 'robots.txt', 5, 'pass', 'robots.txt found (' . strlen($robotsBody) . ' bytes).');
} else {
    $checks[] = check('robots_txt', 'discover', 'robots.txt', 5, 'fail', 'No valid robots.txt was found at /robots.txt.', 'Publish a valid robots.txt at the site root and reference your sitemap.');
}

$aiNames = ['gptbot', 'chatgpt-user', 'oai-searchbot', 'claudebot', 'claude-web', 'anthropic-ai', 'google-extended', 'ccbot', 'perplexitybot', 'perplexity-user', 'applebot-extended', 'amazonbot', 'bytespider', 'meta-externalagent', 'cohere-ai', 'diffbot', 'imagesiftbot', 'youbot'];
$foundAgents = [];
if ($robotsValid) {
    if (preg_match_all('/^\s*User-agent\s*:\s*(.+?)\s*$/im', $robotsBody, $m)) {
        foreach ($m[1] as $agentLine) {
            foreach (preg_split('/[\s,]+/', strtolower($agentLine)) as $agent) {
                if (in_array($agent, $aiNames, true) && !in_array($agent, $foundAgents, true)) {
                    $foundAgents[] = $agent;
                }
            }
        }
    }
}
if (!$robotsValid) {
    $checks[] = check('ai_policy', 'discover', 'AI crawler policy', 5, 'fail', 'No robots.txt means no declared AI crawler policy.', 'Add named User-agent groups for the AI agents you allow or deny.');
} elseif ($foundAgents) {
    $checks[] = check('ai_policy', 'discover', 'AI crawler policy', 5, 'pass', 'Named AI agents found in robots.txt: ' . implode(', ', array_slice($foundAgents, 0, 8)) . '.');
} else {
    $checks[] = check('ai_policy', 'discover', 'AI crawler policy', 5, 'warn', 'robots.txt exists but names no AI crawler agents.', 'Add explicit User-agent groups (allow or deny) for GPTBot, ClaudeBot, CCBot, Google-Extended and others you care about.');
}

$sitemapUrl = '';
if ($robotsValid && preg_match('/^\s*Sitemap\s*:\s*(\S+)/im', $robotsBody, $sm)) {
    $candidate = trim($sm[1]);
    if (preg_match('#^https?://#i', $candidate)) {
        $sitemapUrl = $candidate;
    }
}
if ($sitemapUrl === '') {
    $sitemapUrl = $origin . '/sitemap.xml';
}
$sitemap = http_get($sitemapUrl, 4, 2, 300000);
$sitemapBody = $sitemap['body'];
$sitemapValid = $sitemap['status'] === 200
    && (stripos($sitemapBody, '<urlset') !== false || stripos($sitemapBody, '<sitemapindex') !== false);
if ($sitemapValid) {
    $locCount = preg_match_all('/<loc>/i', $sitemapBody);
    $checks[] = check('sitemap', 'discover', 'XML sitemap', 5, 'pass', 'A valid sitemap was found (' . (int) $locCount . ' URLs).');
} elseif ($sitemap['status'] === 200) {
    $checks[] = check('sitemap', 'discover', 'XML sitemap', 5, 'warn', 'A file was found at the sitemap URL but it is not valid sitemap XML.', 'Return a valid <urlset> or <sitemapindex> document.');
} else {
    $checks[] = check('sitemap', 'discover', 'XML sitemap', 5, 'fail', 'No sitemap was found (HTTP ' . $sitemap['status'] . ').', 'Publish /sitemap.xml and reference it from robots.txt.');
}

$llms = http_get($origin . '/llms.txt', 4, 2, 200000);
$llmsBody = $llms['body'];
$llmsValid = $llms['status'] === 200 && trim($llmsBody) !== '' && !preg_match('#<html#i', $llmsBody);
if ($llmsValid) {
    $checks[] = check('llms_txt', 'discover', 'llms.txt', 5, 'pass', 'llms.txt found (' . strlen($llmsBody) . ' bytes).');
} else {
    $checks[] = check('llms_txt', 'discover', 'llms.txt', 5, 'fail', 'No llms.txt was found.', 'Add a short Markdown map of your key pages at /llms.txt. It is an emerging proposal, not a standard.');
}

/* ---------- operate checks ---------- */
$apiDocLinks = [];
foreach ($xp->query('//a[@href]') as $a) {
    $href = trim($a->getAttribute('href'));
    if ($href === '' || str_starts_with($href, '#')) {
        continue;
    }
    if (preg_match('#openapi|swagger#i', $href)) {
        $apiDocLinks[] = $href;
        continue;
    }
    if (preg_match('#\.(php|json|xml|yaml|yml)(\?|\#|$)#i', $href)) {
        continue;
    }
    if (preg_match('#(^|/)(docs?|documentation|reference|developers)(/|$|\?|\#)#i', $href)) {
        $apiDocLinks[] = $href;
    }
}
$apiDocLinks = array_values(array_unique($apiDocLinks));

$oasCandidates = [];
foreach ($apiDocLinks as $href) {
    if (preg_match('#openapi|swagger#i', $href)) {
        try {
            $oasCandidates[] = absolutize($finalUrl, $href);
        } catch (Throwable $e) {
        }
    }
}
$oasCandidates[] = $origin . '/openapi.json';
$oasCandidates[] = $origin . '/.well-known/openapi.json';
$oasCandidates = array_values(array_unique($oasCandidates));

$oasFound = '';
$oasChecked = 0;
foreach ($oasCandidates as $candidate) {
    if ($oasChecked >= 3) {
        break;
    }
    try {
        validate_url($candidate);
    } catch (Throwable $e) {
        continue;
    }
    $oasChecked++;
    $res = http_get($candidate, 3, 1, 300000);
    $body = $res['body'];
    if ($res['status'] === 200 && (preg_match('/"openapi"\s*:\s*"3/i', $body) || preg_match('/^openapi\s*:\s*["\']?3/im', $body)) && stripos($body, 'paths') !== false) {
        $oasFound = $candidate;
        break;
    }
}
if ($oasFound !== '') {
    $checks[] = check('openapi', 'operate', 'OpenAPI description', 5, 'pass', 'An OpenAPI 3 description was found at ' . mb_substr((string) parse_url($oasFound, PHP_URL_PATH), 0, 200) . '.');
} elseif ($apiDocLinks) {
    $checks[] = check('openapi', 'operate', 'OpenAPI description', 5, 'warn', 'API documentation links were found, but no OpenAPI file at the common locations.', 'Publish openapi.json or link the description directly from your docs.');
} else {
    $checks[] = check('openapi', 'operate', 'OpenAPI description', 5, 'fail', 'No OpenAPI description was found.', 'Publish an OpenAPI 3.1 description at a stable URL and link it from your docs.');
}

if ($apiDocLinks) {
    $checks[] = check('api_docs', 'operate', 'API documentation', 5, 'pass', $apiDocLinks ? (count($apiDocLinks) . ' link(s) to API or developer documentation were found.') : 'API documentation links were found.');
} else {
    $checks[] = check('api_docs', 'operate', 'API documentation', 5, 'fail', 'No links to API or developer documentation were found.', 'Add a public docs or API reference page, and link it from the homepage and llms.txt.');
}

/* ---------- trust checks ---------- */
$finalScheme = strtolower((string) parse_url($finalUrl, PHP_URL_SCHEME));
if ($finalScheme === 'https') {
    $checks[] = check('https', 'trust', 'HTTPS', 5, 'pass', 'The final page is served over HTTPS.');
} else {
    $checks[] = check('https', 'trust', 'HTTPS', 5, 'fail', 'The final page is served over HTTP.', 'Redirect HTTP to HTTPS and publish HSTS.');
}

$security = http_get($origin . '/.well-known/security.txt', 4, 2, 100000);
$securityBody = $security['body'];
$securityValid = $security['status'] === 200 && stripos($securityBody, 'contact:') !== false;
if ($securityValid) {
    $checks[] = check('security_txt', 'trust', 'security.txt', 5, 'pass', 'security.txt found with a Contact field.');
} elseif ($security['status'] === 200) {
    $checks[] = check('security_txt', 'trust', 'security.txt', 5, 'warn', 'A file exists at /.well-known/security.txt but it has no Contact field.', 'Add a Contact field and a future Expires date.');
} else {
    $checks[] = check('security_txt', 'trust', 'security.txt', 5, 'fail', 'No security.txt was found.', 'Publish /.well-known/security.txt with a contact and an expiry date.');
}

/* ---------- localize scanner labels and map to standard anchors ---------- */
$zhLabels = [
    'title' => '页面标题',
    'meta_description' => '元描述',
    'canonical' => '规范链接',
    'lang' => '页面语言',
    'h1' => '标题结构',
    'ssr_content' => '服务端渲染内容',
    'image_alt' => '图片替代文本',
    'viewport' => '移动视口',
    'structured_data' => '结构化数据',
    'open_graph' => 'Open Graph 元数据',
    'robots_txt' => 'robots.txt',
    'ai_policy' => 'AI 爬虫政策',
    'sitemap' => 'XML 站点地图',
    'llms_txt' => 'llms.txt',
    'openapi' => 'OpenAPI 描述',
    'api_docs' => 'API 文档',
    'https' => 'HTTPS',
    'security_txt' => 'security.txt',
];
$stdMap = [
    'title' => 'r3', 'meta_description' => 'r3', 'canonical' => 'r4', 'lang' => 'r10', 'h1' => 'r2',
    'ssr_content' => 'r1', 'image_alt' => 'r8', 'viewport' => 'r10', 'structured_data' => 'r7',
    'open_graph' => 'r10', 'robots_txt' => 'r5', 'ai_policy' => 'r6', 'sitemap' => 'r5',
    'llms_txt' => 'r6', 'openapi' => 'a2', 'api_docs' => 'a1', 'https' => 'g6', 'security_txt' => 'g6',
];
foreach ($checks as &$c) {
    if ($uiLang === 'zh' && isset($zhLabels[$c['id']])) {
        $c['label'] = $zhLabels[$c['id']];
    }
    $c['standard'] = $stdMap[$c['id']] ?? '';
}
unset($c);

/* ---------- scoring ---------- */
$cats = [
    'read' => ['score' => 0.0, 'max' => 0.0],
    'discover' => ['score' => 0.0, 'max' => 0.0],
    'operate' => ['score' => 0.0, 'max' => 0.0],
    'trust' => ['score' => 0.0, 'max' => 0.0],
];
$total = 0.0;
foreach ($checks as $c) {
    $w = (float) $c['weight'];
    $cats[$c['category']]['max'] += $w;
    if ($c['status'] === 'pass') {
        $cats[$c['category']]['score'] += $w;
        $total += $w;
    } elseif ($c['status'] === 'warn') {
        $cats[$c['category']]['score'] += $w / 2;
        $total += $w / 2;
    }
}
$catsOut = [];
foreach ($cats as $k => $v) {
    $catsOut[$k] = ['score' => (int) round($v['score']), 'max' => (int) round($v['max'])];
}
$score = (int) round($total);

if ($score >= 85) {
    $grade = 'Strong signals';
    $summary = 'The public surface is legible. Fix the remaining warnings, then work through Level 2 and 3 by hand — they cannot be fully tested from outside.';
} elseif ($score >= 70) {
    $grade = 'Readable';
    $summary = 'The basics are in place. Close the gaps below before claiming Level 1, then decide whether an API or tool manifest makes sense.';
} elseif ($score >= 45) {
    $grade = 'Getting there';
    $summary = 'An agent can see part of the page, but important signals are missing. Start with server-rendered content, metadata and robots.txt.';
} else {
    $grade = 'Mostly opaque';
    $summary = 'Most automated readers will struggle. Fix the foundations first: HTML content, a title, a description, robots.txt and a sitemap.';
}

if ($uiLang === 'zh') {
    $zhUi = require __DIR__ . '/../app/lang/zh.php';
    $gradeKey = $grade;
    $grade = $zhUi['common']['scan']['grades'][$gradeKey] ?? $grade;
    $summary = $zhUi['common']['scan']['summaries'][$gradeKey] ?? $summary;
    $levelMap = ['Level 2 signals' => '第 2 级信号', 'Level 1 signals' => '第 1 级信号', 'Foundation missing' => '基础缺失'];
}

$essential = ['title', 'meta_description', 'h1', 'ssr_content', 'robots_txt', 'sitemap'];
$byId = [];
foreach ($checks as $c) {
    $byId[$c['id']] = $c['status'];
}
$foundation = true;
foreach ($essential as $id) {
    if (($byId[$id] ?? 'fail') === 'fail') {
        $foundation = false;
    }
}
$level = $foundation && ($byId['openapi'] ?? '') === 'pass' ? 'Level 2 signals' : ($foundation ? 'Level 1 signals' : 'Foundation missing');
if ($uiLang === 'zh') {
    $level = $levelMap[$level] ?? $level;
}

$result = [
    'ok' => true,
    'url' => $info['url'],
    'final_url' => $finalUrl,
    'score' => $score,
    'grade' => $grade,
    'summary' => $summary,
    'level' => $level,
    'categories' => $catsOut,
    'checks' => $checks,
    'scanned_at' => gmdate('c'),
    'cached' => false,
];

$cachePayload = $result;
unset($cachePayload['url'], $cachePayload['final_url'], $cachePayload['cached']);
$cachePayload['at'] = time();
@file_put_contents($cacheFile, json_encode($cachePayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);

/* occasional cache cleanup */
if (random_int(1, 100) === 1) {
    foreach (glob(data_path('cache') . '/*.json') ?: [] as $file) {
        if (filemtime($file) < time() - 172800) {
            @unlink($file);
        }
    }
}

json_out(200, $result);
