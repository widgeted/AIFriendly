<?php
declare(strict_types=1);

function mark_svg(int $size = 30, string $class = '', string $variant = 'mono'): string
{
    static $uid = 0;
    $uid++;
    $c = $class !== '' ? ' ' . e($class) : '';
    $diamond = 'M180,39.4c-5.6,73.1-67.5,135-140.6,140.6c73.1,5.6,135,67.5,140.6,140.6c5.6-73.1,67.5-135,140.6-140.6 C247.5,174.4,185.6,112.5,180,39.4z';
    $ribbonA = 'M149.3,118.6c-0.5,0.3-1,0.5-1.5,0.8c-30,16-56.7,35-76.8,54.5C102.1,164.3,129.6,144.5,149.3,118.6z';
    $ribbonB = 'M322.5,104.2c-9.4-17.7-33.7-25.1-68.5-21c-17.5,2.1-36.9,6.9-57,14.2c3.1,5.8,6.5,11.3,10.3,16.6 c17.3-6,33.9-10.1,49-11.8c5.6-0.7,10.8-1,15.6-1c17.4,0,29.6,4.2,33.7,12c5.3,9.9-1.6,27.5-18.5,47c-19.3,22.4-49.1,45-83.9,63.5 c-34.8,18.5-70.2,30.6-99.6,34.1c-25.6,3-44-1.1-49.3-11c-5.3-9.9,1.6-27.5,18.5-47c2.8-3.3,5.9-6.6,9.1-9.8 c-6.6-2.6-13.4-4.8-20.3-6.4c-1.1,1.3-2.3,2.5-3.4,3.8c-22.8,26.5-30.3,50.8-20.9,68.5c7.7,14.5,25.4,22.1,50.8,22.1 c5.5,0,11.5-0.4,17.7-1.1c31.7-3.8,69.5-16.6,106.3-36.2c36.8-19.6,68.6-43.7,89.5-67.9C324.5,146.2,331.9,121.9,322.5,104.2z';
    // Dot policy: shown from 96px up; motion (orbit + gradient swirl) from 200px up.
    $showDot = $size >= 96;
    $animate = $size >= 200;
    $stroke = '#f8f3ea';
    $fillA = '#f8f3ea';
    $fillB = '#6c5f51';
    $defs = '';
    $swirl = '';
    // Orbit track: the ring's complete loop, computed from the artwork's own
    // geometry, verified to lie on the ring everywhere. It runs: band (home ->
    // elbow), top strip to the pointed tip, behind the spark's upper spike, the
    // crescent (the ring's far side), behind the left spike, then the band
    // again. Offsets are relative to the dot's home position 160.2,253.4, so a
    // browser without SMIL still draws the dot exactly there.
    $orbit = 'M151.3,-143.7L152.4,-141.5L153.2,-139.3L153.8,-136.9L154.1,-134.4L154.3,-131.8L154.3,-129.1L154,-126.4L153.6,-123.5L152.9,-120.6L152,-117.6L151,-114.6L149.7,-111.5L148.2,-108.3L146.5,-105.1L144.6,-101.8L142.5,-98.5L140.3,-95.1L137.8,-91.8L135.2,-88.4L132.4,-84.9L129.3,-81.5L126.2,-78L122.8,-74.6L119.3,-71.1L115.7,-67.7L111.9,-64.2L107.9,-60.8L103.8,-57.4L99.6,-54L95.3,-50.7L90.8,-47.3L86.2,-44.1L81.5,-40.9L76.8,-37.7L71.9,-34.6L66.9,-31.5L61.9,-28.5L56.8,-25.6L51.6,-22.8L46.4,-20L41.2,-17.3L35.9,-14.7L30.6,-12.2L25.2,-9.8L19.9,-7.5L14.6,-5.3L9.2,-3.2L3.9,-1.3L-1.4,0.6L-6.7,2.4L-11.9,4L-17.1,5.5L-22.3,6.9L-27.4,8.2L-32.4,9.3L-37.3,10.3L-42.1,11.2L-46.9,11.9L-51.5,12.5L-56.1,13L-60.5,13.4L-64.8,13.6L-69,13.6L-73,13.6L-76.9,13.4L-80.7,13L-84.3,12.6L-87.7,12L-91,11.2L-94.1,10.4L-97.1,9.4L-99.8,8.2L-102.4,7L-104.8,5.6L-107,4.1L-109,2.5L-110.9,0.7L-112.5,-1.1L-113.9,-3.1L-115.1,-5.2L-116.1,-7.4L-116.9,-9.7L-117.5,-12.1L-117.9,-14.6L-118.1,-17.2L-118,-19.8L-117.8,-22.6L-117.3,-25.4L-116.7,-28.3L-115.8,-31.3L-114.7,-34.4L-113.4,-37.5L-112,-40.6L-110.3,-43.9L-108.4,-47.1L-106.3,-50.4L-104,-53.8L-101.6,-57.2L-98.9,-60.6L-96.1,-64L-93.1,-67.4L-89.9,-70.9L-86.6,-74.4L-83.1,-77.8L-79.4,-81.3L-75.6,-84.7L-71.7,-88.1L-67.6,-91.6L-63.4,-94.9L-59,-98.3L-54.6,-101.6L-50,-104.9L-45.3,-108.1L-40.5,-111.3L-35.6,-114.4L-30.7,-117.4L-25.7,-120.4L-20.6,-123.3L-15.4,-126.2L-10.2,-128.9L-4.9,-131.6L0.3,-134.2L5.7,-136.7L11,-139.1L16.3,-141.4L21.7,-143.6L27,-145.7L32.4,-147.7L37.7,-149.5L42.9,-151.3L48.2,-152.9L53.4,-154.4L58.5,-155.8L63.6,-157.1L68.6,-158.2L73.5,-159.2L78.4,-160.1L83.1,-160.9L87.8,-161.5L92.3,-162L96.7,-162.3L101,-162.5L105.2,-162.6L109.3,-162.5L113.2,-162.3L116.9,-162L120.5,-161.5L124,-160.9L127.3,-160.2L130.4,-159.3L133.3,-158.3L136.1,-157.2L138.7,-155.9L141.1,-154.5L143.3,-153L145.3,-151.4L147.1,-149.7L148.7,-147.8L150.1,-145.8Z';
    // The spark is split into two layers: the back piece sits under the ring,
    // the front piece (same silhouette, fill only, clipped to the upper-left
    // half) sits over the ring and the bead. Anything that passes behind the
    // spark is therefore covered by real geometry — no masks, no fades. The
    // clip edge runs through the ring's hole, so the seam is invisible (same
    // fill) and never crosses the drawn ribbon.
    $clipId = 'af-ul-' . $uid;
    $clipPoly = '-364.9,393.1 690.9,-57.1 494.8,-517.1 -561.1,-66.8';
    if ($variant === 'spectrum') {
        $g1 = 'af-g1-' . $uid;
        $g2 = 'af-g2-' . $uid;
        $stops = '<stop offset="0" stop-color="#FFB000"/><stop offset="0.25" stop-color="#FF5A00"/><stop offset="0.5" stop-color="#E01B7A"/><stop offset="0.75" stop-color="#8B1BB0"/><stop offset="1" stop-color="#2B0B8A"/>';
        if ($animate) {
            // SMIL: ribbon colours rotate with the orbit. CSS handles the dot so
            // prefers-reduced-motion (site.css) can stop it.
            $swirl = '<animateTransform attributeName="gradientTransform" type="rotate" from="0 180 180" to="360 180 180" dur="14s" repeatCount="indefinite"/>';
        }
        $defs = '<defs>'
            . '<linearGradient id="' . $g1 . '" gradientUnits="userSpaceOnUse" x1="70.9486" y1="146.2861" x2="149.2846" y2="146.2861">' . $stops . $swirl . '</linearGradient>'
            . '<linearGradient id="' . $g2 . '" gradientUnits="userSpaceOnUse" x1="33.4947" y1="180.0001" x2="326.5053" y2="180.0001">' . $stops . $swirl . '</linearGradient>'
            . '</defs>';
        $stroke = '#ffffff';
        $fillA = 'url(#' . $g1 . ')';
        $fillB = 'url(#' . $g2 . ')';
    }
    $defs .= '<clipPath id="' . $clipId . '" clipPathUnits="userSpaceOnUse"><polygon points="' . $clipPoly . '"/></clipPath>';
    // The dot rides the ribbon. At motion sizes it follows the orbit track
    // (SMIL, same 14s clock as the gradient swirl) and hides behind the spark;
    // a static twin covers reduced-motion / no-SMIL so the dot never jumps.
    if (!$showDot) {
        $dot = '';
    } elseif ($animate) {
        $orbId = 'af-orb-' . $uid;
        $defs .= '<path id="' . $orbId . '" d="' . $orbit . '" fill="none" stroke="none"/>';
        $dotMarkup = '<circle cx="160.2" cy="253.4" r="15.7" fill="#dc5000" stroke="' . $stroke . '" stroke-width="2"/>';
        $dot = '<g class="mark__orbit">' . $dotMarkup
            . '<animateMotion dur="14s" repeatCount="indefinite" calcMode="paced"><mpath xlink:href="#' . $orbId . '" href="#' . $orbId . '"/></animateMotion>'
            . '</g>'
            . '<circle class="mark__dot-static" cx="160.2" cy="253.4" r="15.7" fill="#dc5000" stroke="' . $stroke . '" stroke-width="2"/>';
    } else {
        $dot = '<circle cx="160.2" cy="253.4" r="15.7" fill="#dc5000" stroke="' . $stroke . '" stroke-width="2"/>';
    }
    $out = '<svg class="mark' . $c . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 360 360" aria-hidden="true" focusable="false"'
        . ($animate ? ' xmlns:xlink="http://www.w3.org/1999/xlink"' : '') . '>'
        . $defs
        . '<path d="' . $diamond . '" fill="currentColor" stroke="' . $stroke . '" stroke-width="2"/>'
        . '<path d="' . $ribbonA . '" fill="' . $fillA . '" stroke="' . $stroke . '" stroke-width="2"/>'
        . '<path d="' . $ribbonB . '" fill="' . $fillB . '" stroke="' . $stroke . '" stroke-width="2"/>'
        . $dot
        . '<path d="' . $diamond . '" fill="currentColor" clip-path="url(#' . $clipId . ')"/>'
        . '<path d="' . $diamond . '" fill="none" stroke="' . $stroke . '" stroke-width="2" clip-path="url(#' . $clipId . ')"/>'
        . '</svg>';
    return $out;
}

function version_assets(string $html): string
{
    if (!defined('MEDIA_VERSION')) {
        return $html;
    }
    $v = e(MEDIA_VERSION);
    $strip = static fn (array $m): string => preg_replace('~\?.*$~', '', $m[0]) . '?v=' . $v;
    $html = preg_replace_callback(
        '~(?<![\w./-])(?:https://aifriendly\.fyi)?/assets/(?:badges|brand|kit)/[A-Za-z0-9._/-]+(?:\?[A-Za-z0-9._\~%=&-]*)?~',
        $strip,
        $html
    ) ?? $html;
    $html = preg_replace_callback(
        '~(?<![\w./-])(?:https://aifriendly\.fyi)?/(?:og-(?:en|zh)\.png|favicon\.svg|favicon-32\.png|apple-touch-icon\.png|icon-(?:192|512)\.png)(?:\?[A-Za-z0-9._\~%=&-]*)?~',
        $strip,
        $html
    ) ?? $html;
    return $html;
}

function arrow_icon(): string
{
    return '<svg class="icon-arrow" width="15" height="15" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path d="M2.5 8h10M8.5 3.5 13 8l-4.5 4.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

function check_icon(): string
{
    return '<svg class="icon-check" width="14" height="14" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path d="m3 8.5 3.2 3.2L13 4.8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

function copy_icon(): string
{
    return '<svg class="icon-copy" width="14" height="14" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><rect x="5" y="5" width="8" height="9" rx="1.6" fill="none" stroke="currentColor" stroke-width="1.3"/><path d="M11 5V3.6A1.6 1.6 0 0 0 9.4 2H3.6A1.6 1.6 0 0 0 2 3.6v7.8A1.6 1.6 0 0 0 3.6 13H5" fill="none" stroke="currentColor" stroke-width="1.3"/></svg>';
}

function menu_icon(): string
{
    return '<span class="menu-icon" aria-hidden="true"><i></i><i></i><i></i></span>';
}

function btn(string $label, string $href, string $variant = 'primary', string $extra = ''): string
{
    $class = 'btn btn--' . e($variant);
    $ext = $extra !== '' ? ' ' . $extra : '';
    return '<a class="' . $class . '" href="' . e($href) . '"' . $ext . '><span>' . $label . '</span>' . arrow_icon() . '</a>';
}

function section_head(string $eyebrow, string $title, string $lede = '', string $id = ''): string
{
    $idAttr = $id !== '' ? ' id="' . e($id) . '"' : '';
    $out = '<div class="section-head reveal"' . $idAttr . '>';
    $out .= '<div class="section-head__title"><p class="eyebrow"><span class="spectrum-dot" aria-hidden="true"></span>' . $eyebrow . '</p><h2 class="h2">' . $title . '</h2></div>';
    if ($lede !== '') {
        $out .= '<p class="lede section-head__lede">' . $lede . '</p>';
    }
    $out .= '</div>';
    return $out;
}

function faq_list(array $items, string $class = ''): string
{
    $out = '<div class="faq ' . e($class) . '">';
    foreach ($items as $item) {
        $q = is_array($item['q'] ?? null) ? tr($item['q']) : (string) ($item['q'] ?? '');
        $a = is_array($item['a'] ?? null) ? tr($item['a']) : (string) ($item['a'] ?? '');
        $out .= '<details><summary><span>' . $q . '</span><span class="faq__plus" aria-hidden="true"></span></summary><div class="faq__answer">' . links($a) . '</div></details>';
    }
    $out .= '</div>';
    return $out;
}

function check_item(array $check, string $auditLabel): string
{
    $title = tr($check['title']);
    $desc = tr($check['desc']);
    $why = tr($check['why']);
    $how = tr($check['how']);
    $id = e($check['id']);
    return '<article class="check" id="' . $id . '">'
        . '<span class="check__num">' . strtoupper($id) . '</span>'
        . '<div class="check__body"><h3>' . $title . '</h3><p>' . $desc . '</p>'
        . '<details class="check__more"><summary>' . $auditLabel . '</summary><div class="check__detail"><p><strong>' . t('standard.why_label') . '</strong> ' . $why . '</p><p><strong>' . t('standard.how_label') . '</strong> ' . $how . '</p></div></details>'
        . '</div></article>';
}

function audit_list(array $checks): string
{
    $groups = [1 => [], 2 => [], 3 => []];
    foreach ($checks as $check) {
        $groups[(int) $check['level']][] = $check;
    }
    $out = '';
    foreach ([1, 2, 3] as $level) {
        $out .= '<div class="audit-group">';
        $out .= '<h3>' . e(t('standard.level')) . ' ' . $level . ' — ' . e(t('standard.level_' . $level . '_name')) . ' <span>' . count($groups[$level]) . ' ' . e(t('common.labels.checks')) . '</span></h3>';
        foreach ($groups[$level] as $check) {
            $id = 'c-' . e($check['id']);
            $title = tr($check['title']);
            $desc = tr($check['desc']);
            $out .= '<div class="audit-item"><input type="checkbox" id="' . $id . '" data-check="l' . $level . '">'
                . '<label for="' . $id . '"><strong>' . strtoupper(e($check['id'])) . ' · ' . $title . '</strong><span>' . $desc . '</span></label>'
                . '<span class="audit-item__tag">L' . $level . '</span></div>';
        }
        $out .= '</div>';
    }
    return $out;
}

function field(string $name, string $label, string $type = 'text', string $extra = '', string $hint = ''): string
{
    $id = 'f-' . e($name);
    $out = '<div class="field"><label for="' . $id . '">' . e($label) . '</label>';
    if ($type === 'textarea') {
        $out .= '<textarea id="' . $id . '" name="' . e($name) . '" ' . $extra . '></textarea>';
    } else {
        $out .= '<input id="' . $id . '" name="' . e($name) . '" type="' . e($type) . '" ' . $extra . '>';
    }
    if ($hint !== '') {
        $out .= '<span class="hint">' . $hint . '</span>';
    }
    $out .= '</div>';
    return $out;
}

function honeypot(): string
{
    return '<input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">';
}
