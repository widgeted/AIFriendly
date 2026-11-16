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
    $orbit = 'M0,0C2.6,-1 7,-2.6 10.4,-3.9C13.8,-5.3 17.3,-6.8 20.7,-8.2C24.1,-9.7 27.4,-11.3 30.8,-12.8C34.1,-14.4 37.5,-16.1 40.8,-17.7C44.1,-19.4 47.4,-21.1 50.6,-22.9C53.9,-24.7 57.1,-26.5 60.3,-28.4C63.5,-30.2 66.7,-32.1 69.8,-34.1C73,-36 76.1,-38 79.2,-40.1C82.3,-42.1 85.4,-44.2 88.4,-46.4C91.4,-48.5 94.4,-50.7 97.3,-53C100.3,-55.2 103.2,-57.5 106.1,-59.9C108.9,-62.2 111.7,-64.6 114.5,-67.1C117.3,-69.6 120,-72.1 122.6,-74.7C125.3,-77.3 127.9,-79.9 130.4,-82.7C132.9,-85.4 135.3,-88.2 137.6,-91.1C139.9,-94 142.2,-97 144.2,-100C146.3,-103.1 148.3,-106.3 150,-109.6C151.7,-112.9 153.3,-116.3 154.3,-119.8C155.4,-123.3 156.3,-127 156.4,-130.7C156.4,-134.3 155.9,-138.1 154.7,-141.5C153.5,-144.9 151.6,-148.3 149.1,-150.9C146.6,-153.4 143.1,-155.3 139.8,-156.8C136.5,-158.4 132.8,-159.3 129.2,-160.1C125.6,-160.9 121.9,-161.3 118.2,-161.5C114.5,-161.8 110.8,-161.8 107.1,-161.6C103.4,-161.5 99.7,-161.2 96,-160.8C92.3,-160.4 88.6,-159.9 85,-159.2C81.3,-158.6 77.7,-157.9 74.1,-157.1C70.4,-156.3 66.9,-154.6 63.3,-154.4C59.6,-154.2 56,-155.5 52.4,-156C48.8,-156.5 44.8,-158.4 41.5,-157.6C38.2,-156.9 35.3,-153.9 32.5,-151.6C29.7,-149.3 27.3,-146.3 24.7,-143.7C22.1,-141.1 19.4,-138.4 16.8,-135.8C14.2,-133.2 11.6,-130.5 9,-127.9C6.4,-125.3 3.7,-122.6 1.1,-120C-1.4,-117.4 -4,-114.9 -6.4,-112.3C-8.8,-109.8 -11.1,-107.1 -13.5,-104.7C-16,-102.3 -18.6,-100.1 -21.2,-97.9C-23.9,-95.7 -26.6,-93.6 -29.4,-91.4C-32.1,-89.3 -34.8,-87 -37.6,-84.9C-40.5,-82.8 -43.3,-80.4 -46.6,-78.9C-49.8,-77.3 -53.7,-76.7 -57.2,-75.6C-60.7,-74.5 -64.3,-73.5 -67.9,-72.4C-71.4,-71.4 -75,-70.3 -78.5,-69.3C-82.1,-68.2 -86.1,-67.9 -89.1,-65.9C-92.1,-64 -94.1,-60.5 -96.5,-57.6C-98.9,-54.8 -101.2,-51.9 -103.3,-48.8C-105.4,-45.8 -107.5,-42.7 -109.2,-39.4C-111,-36.2 -112.8,-32.9 -114,-29.4C-115.3,-26 -116.3,-22.3 -116.7,-18.7C-117,-15 -117,-11.1 -116,-7.7C-115.1,-4.3 -113.2,-0.8 -111,1.9C-108.7,4.7 -105.7,7.1 -102.5,8.9C-99.4,10.7 -95.7,11.8 -92.2,12.8C-88.6,13.7 -84.9,14.2 -81.2,14.5C-77.5,14.9 -73.8,15 -70.1,14.9C-66.4,14.9 -62.7,14.6 -59,14.3C-55.3,13.9 -51.6,13.4 -48,12.9C-44.3,12.3 -40.6,11.6 -37,10.8C-33.4,10.1 -29.8,9.2 -26.2,8.2C-22.6,7.3 -19.1,6.3 -15.5,5.2C-12,4.1 -7.5,2.6 -4.9,1.8C-2.3,0.9 -2.6,1 0,0Z';
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
        . '<path d="' . $diamond . '" fill="currentColor" stroke="' . $stroke . '" stroke-width="2"/>'
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
