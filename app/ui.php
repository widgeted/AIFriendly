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
    $orbit = 'M151.381,-143.677C167.007,-113.59 120.01,-58.213 46.412,-19.991C-27.187,18.232 -99.518,24.827 -115.143,-5.261C-130.769,-35.348 -83.772,-90.725 -10.174,-128.947C63.425,-167.17 135.756,-173.765 151.381,-143.677Z';
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
            . '<animateMotion dur="14s" repeatCount="indefinite" calcMode="linear" keyPoints="0;0.006;0.012;0.018;0.024;0.031;0.039;0.047;0.055;0.064;0.073;0.083;0.094;0.105;0.117;0.129;0.141;0.154;0.167;0.18;0.194;0.208;0.222;0.236;0.25;0.264;0.278;0.292;0.306;0.32;0.333;0.346;0.359;0.371;0.383;0.395;0.406;0.417;0.427;0.436;0.445;0.453;0.461;0.469;0.476;0.482;0.488;0.494;0.5;0.506;0.512;0.518;0.524;0.531;0.539;0.547;0.555;0.564;0.573;0.583;0.594;0.605;0.617;0.629;0.641;0.654;0.667;0.68;0.694;0.708;0.722;0.736;0.75;0.764;0.778;0.792;0.806;0.82;0.833;0.846;0.859;0.871;0.883;0.895;0.906;0.917;0.927;0.936;0.945;0.953;0.961;0.969;0.976;0.982;0.988;0.994;1" keyTimes="0;0.011;0.021;0.031;0.04;0.049;0.058;0.067;0.075;0.084;0.093;0.102;0.112;0.122;0.132;0.142;0.153;0.165;0.176;0.188;0.2;0.213;0.225;0.237;0.25;0.263;0.275;0.287;0.3;0.312;0.324;0.335;0.347;0.358;0.368;0.378;0.388;0.398;0.407;0.416;0.425;0.433;0.442;0.451;0.46;0.469;0.479;0.489;0.5;0.511;0.521;0.531;0.54;0.549;0.558;0.567;0.575;0.584;0.593;0.602;0.612;0.622;0.632;0.642;0.653;0.665;0.676;0.688;0.7;0.713;0.725;0.737;0.75;0.763;0.775;0.787;0.8;0.812;0.824;0.835;0.847;0.858;0.868;0.878;0.888;0.898;0.907;0.916;0.925;0.933;0.942;0.951;0.96;0.969;0.979;0.989;1"><mpath xlink:href="#' . $orbId . '" href="#' . $orbId . '"/></animateMotion>'
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
