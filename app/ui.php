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
    // Orbit track: the ribbon's true centerline, computed from the artwork's
    // own geometry (cross-section pairing of the band's two edges, verified to
    // stay on the band everywhere). Offsets are relative to the dot's home
    // position 160.2,253.4, so a browser without SMIL still draws the dot
    // exactly there. Occlusion is real, not faded: the dot layer carries a
    // mask that hides it wherever the spark silhouette covers it, except
    // along $crossCorridor — the arc where the band crosses in front of the
    // spark — which is re-opened with a white stroke. The result is a
    // crescent-shaped disappearance behind the spark edge at the band tip and
    // the matching re-emergence at the left tip.
    $orbit = 'M0,0C2.6,-1 7,-2.6 10.4,-4C13.9,-5.3 17.3,-6.8 20.7,-8.3C24.1,-9.8 27.5,-11.3 30.9,-12.9C34.3,-14.5 37.6,-16.1 40.9,-17.8C44.2,-19.5 47.5,-21.2 50.8,-23C54.1,-24.8 57.3,-26.6 60.5,-28.5C63.7,-30.4 66.9,-32.3 70.1,-34.2C73.2,-36.2 76.4,-38.2 79.5,-40.3C82.6,-42.3 85.7,-44.4 88.7,-46.6C91.7,-48.8 94.7,-51 97.7,-53.2C100.6,-55.5 103.5,-57.8 106.4,-60.2C109.3,-62.5 112.1,-64.9 114.9,-67.4C117.6,-69.9 120.4,-72.5 123,-75.1C125.7,-77.7 128.3,-80.4 130.8,-83.1C133.3,-85.9 135.7,-88.7 138,-91.6C140.3,-94.5 142.6,-97.5 144.6,-100.6C146.7,-103.7 148.7,-106.9 150.3,-110.2C152,-113.5 153.5,-117 154.5,-120.5C155.5,-124 156.4,-127.8 156.4,-131.4C156.4,-135.1 155.8,-139 154.5,-142.3C153.2,-145.6 151.1,-149 148.5,-151.5C145.9,-153.9 142.3,-155.7 139,-157.2C135.6,-158.7 131.9,-159.6 128.3,-160.3C124.6,-161 120.9,-161.4 117.2,-161.6C113.5,-161.8 109.7,-161.8 106,-161.6C102.3,-161.5 98.6,-161.1 94.9,-160.7C91.2,-160.3 87.5,-159.7 83.9,-159.1C80.2,-158.4 76.6,-157.7 72.9,-156.8C69.3,-156 65.7,-155.1 62.1,-154.1C58.5,-153.1 54.8,-151.2 51.4,-150.9C48,-150.6 44.7,-151.9 41.7,-152.2C38.7,-152.4 36.1,-153.8 33.4,-152.5C30.7,-151.2 28.2,-147.2 25.5,-144.6C22.9,-141.9 20.3,-139.3 17.7,-136.7C15.1,-134 12.4,-131.4 9.8,-128.7C7.2,-126.1 4.5,-123.4 1.9,-120.8C-0.6,-118.2 -3.2,-115.6 -5.7,-113C-8.2,-110.5 -10.5,-107.9 -12.9,-105.5C-15.4,-103 -17.7,-100.5 -20.3,-98.2C-22.9,-96 -25.7,-93.9 -28.5,-91.8C-31.2,-89.6 -33.9,-87.4 -36.8,-85.3C-39.7,-83.2 -42.6,-80.9 -45.8,-79.3C-49.1,-77.7 -52.9,-76.9 -56.4,-75.8C-60,-74.7 -63.5,-73.7 -67.1,-72.7C-70.7,-71.6 -74.2,-70.5 -77.8,-69.5C-81.4,-68.4 -85.5,-68.2 -88.5,-66.3C-91.5,-64.4 -93.7,-61 -96.1,-58.1C-98.5,-55.3 -100.8,-52.4 -103,-49.3C-105.1,-46.3 -107.1,-43.2 -109,-39.9C-110.8,-36.7 -112.6,-33.4 -113.9,-29.9C-115.2,-26.5 -116.2,-22.8 -116.6,-19.2C-117,-15.5 -117,-11.6 -116.1,-8.1C-115.2,-4.7 -113.5,-1.2 -111.2,1.6C-109,4.4 -106,6.8 -102.8,8.7C-99.7,10.5 -96,11.7 -92.5,12.7C-88.9,13.7 -85.2,14.1 -81.5,14.5C-77.8,14.9 -74,15 -70.3,14.9C-66.6,14.9 -62.9,14.7 -59.2,14.3C-55.5,14 -51.8,13.5 -48.1,12.9C-44.4,12.3 -40.8,11.6 -37.1,10.9C-33.5,10.1 -29.9,9.2 -26.3,8.3C-22.7,7.3 -19.1,6.3 -15.5,5.2C-12,4.1 -7.5,2.7 -4.9,1.8C-2.3,0.9 -2.6,1 0,0Z';
    // Where the band passes in front of the spark (the star's deep SE dip
    // splits the crossing into two patches), the mask re-opens the dot.
    $crossCorridor = 'M299.2,160.6L294.9,165.8L290.5,170.9L285.6,176L280.7,180.8L275.8,185.4L270.7,189.8L265.5,194.1L260.5,198.1L255.2,202.2L249.7,206.2L244.2,210.1L238.6,213.9L233.2,217.4L227.4,220.9L221.6,224.4L215.8,227.8L210.2,230.8L204.2,234L198.2,237.1L192.1,240L186.3,242.7L180.1,245.5L173.9,248.1L167.6,250.6L161.3,253L155.3,255.2L148.9,257.3L142.4,259.3';
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
    // Transparent knockout between ring and spark: the spark is cut back
    // wherever the ring comes near it, so the surface shows through the gap.
    // The gap is empty by construction — never a painted spacer colour.
    $koId = 'af-ko-' . $uid;
    $defs .= '<mask id="' . $koId . '" maskUnits="userSpaceOnUse" x="-20" y="-20" width="400" height="400">'
        . '<rect x="-20" y="-20" width="400" height="400" fill="#fff"/>'
        . '<path d="' . $ribbonA . '" fill="#000" stroke="#000" stroke-width="12" stroke-linejoin="round"/>'
        . '<path d="' . $ribbonB . '" fill="#000" stroke="#000" stroke-width="12" stroke-linejoin="round"/>'
        . '</mask>';
    // The dot rides the ribbon. At motion sizes it follows the orbit track
    // (SMIL, same 14s clock as the gradient swirl) and hides behind the spark;
    // a static twin covers reduced-motion / no-SMIL so the dot never jumps.
    if (!$showDot) {
        $dot = '';
    } elseif ($animate) {
        $orbId = 'af-orb-' . $uid;
        $maskId = 'af-dm-' . $uid;
        // Occlusion mask: white canvas, black spark silhouette, then the
        // front-crossing arc re-opened in white. userSpaceOnUse keeps the
        // mask fixed in the viewBox while animateMotion translates the dot.
        $defs .= '<path id="' . $orbId . '" d="' . $orbit . '" fill="none" stroke="none"/>'
            . '<mask id="' . $maskId . '" maskUnits="userSpaceOnUse" x="0" y="0" width="360" height="360">'
            . '<rect width="360" height="360" fill="#fff"/>'
            . '<path d="' . $diamond . '" fill="#000"/>'
            . '<path d="' . $crossCorridor . '" fill="none" stroke="#fff" stroke-width="35" stroke-linecap="round" stroke-linejoin="round"/>'
            . '</mask>';
        $dotMarkup = '<circle cx="160.2" cy="253.4" r="15.7" fill="#dc5000" stroke="' . $stroke . '" stroke-width="2"/>';
        $dot = '<g mask="url(#' . $maskId . ')"><g class="mark__orbit">' . $dotMarkup
            . '<animateMotion dur="14s" repeatCount="indefinite" calcMode="paced"><mpath xlink:href="#' . $orbId . '" href="#' . $orbId . '"/></animateMotion>'
            . '</g></g>'
            . '<circle class="mark__dot-static" cx="160.2" cy="253.4" r="15.7" fill="#dc5000" stroke="' . $stroke . '" stroke-width="2"/>';
    } else {
        $dot = '<circle cx="160.2" cy="253.4" r="15.7" fill="#dc5000" stroke="' . $stroke . '" stroke-width="2"/>';
    }
    $out = '<svg class="mark' . $c . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 360 360" aria-hidden="true" focusable="false"'
        . ($animate ? ' xmlns:xlink="http://www.w3.org/1999/xlink"' : '') . '>'
        . $defs
        . '<path d="' . $diamond . '" fill="currentColor" stroke="' . $stroke . '" stroke-width="2" mask="url(#' . $koId . ')"/>'
        . '<path d="' . $ribbonA . '" fill="' . $fillA . '" stroke="' . $stroke . '" stroke-width="2"/>'
        . '<path d="' . $ribbonB . '" fill="' . $fillB . '" stroke="' . $stroke . '" stroke-width="2"/>'
        . $dot
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
