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
    $orbit = 'M0,0C-0.3,0.1 2.7,-1 4.9,-1.8C7.1,-2.6 9.3,-3.5 11.5,-4.4C13.6,-5.3 15.8,-6.2 18,-7.1C20.1,-8 22.3,-9 24.4,-9.9C26.6,-10.9 28.7,-11.9 30.8,-12.9C33,-13.9 35.1,-14.9 37.2,-15.9C39.3,-17 41.4,-18 43.5,-19.1C45.6,-20.2 47.6,-21.3 49.7,-22.4C51.8,-23.5 53.8,-24.7 55.9,-25.8C57.9,-27 60,-28.2 62,-29.4C64,-30.5 66.1,-31.8 68.1,-33C70.1,-34.2 72.1,-35.5 74,-36.7C76,-38 78,-39.3 79.9,-40.6C81.9,-41.9 83.8,-43.2 85.8,-44.6C87.7,-45.9 89.6,-47.3 91.5,-48.6C93.4,-50 95.3,-51.4 97.2,-52.9C99.1,-54.3 100.9,-55.7 102.8,-57.2C104.6,-58.7 106.4,-60.2 108.2,-61.7C110,-63.2 111.8,-64.7 113.6,-66.3C115.3,-67.8 117.1,-69.4 118.8,-71C120.5,-72.6 122.2,-74.3 123.9,-75.9C125.5,-77.6 127.2,-79.3 128.8,-81C130.4,-82.7 132,-84.4 133.5,-86.2C135,-88 136.6,-89.8 138,-91.6C139.5,-93.5 140.9,-95.3 142.3,-97.2C143.7,-99.1 145,-101.1 146.2,-103.1C147.5,-105.1 148.7,-107.1 149.8,-109.2C150.8,-111.3 151.9,-113.4 152.8,-115.6C153.6,-117.7 154.5,-120 155,-122.2C155.6,-124.5 156.1,-126.9 156.2,-129.2C156.4,-131.5 156.3,-133.9 155.8,-136.2C155.3,-138.5 154.3,-140.7 153.4,-142.8C152.4,-144.9 151.4,-147.2 150,-149C148.6,-150.8 146.7,-152.3 144.8,-153.7C142.9,-155 140.7,-156.1 138.6,-157C136.4,-157.9 134.2,-158.7 131.9,-159.3C129.6,-159.9 127.3,-160.3 125,-160.7C122.7,-161 120.3,-161.2 118,-161.4C115.6,-161.5 113.3,-161.6 110.9,-161.6C108.6,-161.6 106.2,-161.5 103.9,-161.4C101.5,-161.2 99.2,-161 96.8,-160.8C94.5,-160.6 92.2,-160.3 89.8,-159.9C87.5,-159.6 85.2,-159.2 82.9,-158.8C80.6,-158.4 78.3,-157.9 76,-157.4C73.7,-156.9 71.4,-156.4 69.1,-155.8C66.8,-155.3 64.5,-154.7 62.2,-154.1C60,-153.5 57.7,-152.6 55.4,-152.2C53.1,-151.8 50.8,-151.8 48.4,-151.7C46.1,-151.6 43.7,-151.8 41.4,-151.4C39.1,-151.1 36.8,-150.4 34.6,-149.6C32.4,-148.7 30.3,-147.6 28.3,-146.5C26.2,-145.4 24.2,-144.2 22.3,-142.8C20.4,-141.4 18.6,-139.8 16.9,-138.3C15.1,-136.7 13.4,-135.1 11.8,-133.4C10.2,-131.7 8.6,-129.9 7.2,-128C5.9,-126.1 4.6,-124.1 3.5,-122.1C2.3,-120 1.5,-117.7 0.4,-115.7C-0.8,-113.7 -2.1,-111.7 -3.7,-110C-5.2,-108.2 -7.2,-106.9 -8.9,-105.2C-10.6,-103.6 -12,-101.7 -13.7,-100.1C-15.4,-98.5 -17.2,-97 -19,-95.5C-20.8,-94 -22.5,-92.4 -24.4,-90.9C-26.2,-89.5 -28.2,-88.2 -30.1,-86.8C-32,-85.5 -33.8,-84 -35.8,-82.7C-37.7,-81.4 -39.6,-80 -41.7,-78.9C-43.8,-77.8 -46,-76.9 -48.2,-76.1C-50.4,-75.3 -52.7,-74.7 -55,-74.1C-57.2,-73.6 -59.6,-73.3 -61.9,-72.9C-64.2,-72.5 -66.6,-72.3 -68.9,-72C-71.2,-71.6 -73.6,-71.3 -75.8,-70.8C-78.1,-70.2 -80.5,-69.7 -82.6,-68.8C-84.7,-67.9 -86.8,-66.6 -88.7,-65.2C-90.6,-63.8 -92.3,-62.2 -93.9,-60.6C-95.6,-58.9 -97,-57 -98.5,-55.2C-100,-53.3 -101.4,-51.5 -102.8,-49.5C-104.1,-47.6 -105.5,-45.7 -106.7,-43.7C-108,-41.7 -109.2,-39.7 -110.3,-37.6C-111.4,-35.5 -112.4,-33.4 -113.3,-31.2C-114.2,-29.1 -115,-26.8 -115.6,-24.6C-116.2,-22.3 -116.6,-20 -116.8,-17.6C-117,-15.3 -116.9,-12.9 -116.6,-10.6C-116.3,-8.3 -115.9,-5.9 -114.9,-3.8C-114,-1.7 -112.5,0.3 -110.9,2C-109.4,3.8 -107.7,5.4 -105.8,6.8C-103.9,8.2 -101.8,9.3 -99.7,10.3C-97.6,11.2 -95.3,11.9 -93,12.5C-90.8,13.2 -88.4,13.6 -86.1,14C-83.8,14.3 -81.4,14.5 -79.1,14.7C-76.8,14.9 -74.4,14.9 -72.1,14.9C-69.7,14.9 -67.3,14.9 -65,14.7C-62.7,14.6 -60.3,14.4 -58,14.2C-55.6,14 -53.3,13.7 -51,13.3C-48.6,13 -46.3,12.6 -44,12.2C-41.7,11.8 -39.4,11.3 -37.1,10.8C-34.8,10.3 -32.5,9.8 -30.2,9.3C-27.9,8.7 -25.6,8.1 -23.4,7.5C-21.1,6.9 -18.8,6.2 -16.6,5.5C-14.3,4.9 -12.1,4.2 -9.9,3.4C-7.6,2.7 -4.6,1.6 -3.2,1.1C-2.5,0.9 -2.4,0.9 -1.7,0.6Z';
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
