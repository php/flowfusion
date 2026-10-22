--TEST--
Translated seed (inferredbugs-csharp: Native.Framework/2)
--FILE--
<?php
function cq_trope(string $str, bool $commaTrope = false): string {
    $str = str_replace('&', '&amp;', $str);
    $str = str_replace('[', '&#91;', $str);
    $str = str_replace(']', '&#93;', $str);
    if ($commaTrope) $str = str_replace(',', '&#44;', $str);
    return $str;
}

function cq_untrope(string $str): string {
    $str = str_replace('&#91;', '[', $str);
    $str = str_replace('&#93;', ']', $str);
    $str = str_replace('&#44;', ',', $str);
    return str_replace('&amp;', '&', $str);
}

function cq_share_link(string $url, string $title, string $content, string $imgUrl): string {
    $s = ',url=' . cq_trope($url, true);
    if ($title !== '') $s .= ',title=' . cq_trope($title, true);
    if ($content !== '') $s .= ',content=' . cq_trope($content, true);
    if ($imgUrl !== '') $s .= ',image=' . cq_trope($imgUrl, true);
    return '[CQ:share' . $s . ']';
}

function cq_music_diy(string $url, string $musicUrl, ?string $title = null, ?string $content = null, ?string $imgUrl = null): string {
    $s = ',url=' . cq_trope($url, true) . ',audio=' . cq_trope($musicUrl, true);
    if ($title !== null && $title !== '') $s .= ',title=' . cq_trope($title, true);
    if ($content !== null && $content !== '') $s .= ',content=' . cq_trope($content, true);
    if ($imgUrl !== null && $imgUrl !== '') $s .= ',image=' . cq_trope($imgUrl, true);
    return '[CQ:music,type=custom' . $s . ']';
}

$input = "a&b[c],d]e";
$escaped = cq_trope($input, true);
$roundtrip = cq_untrope($escaped);
$share = cq_share_link($input, $input, '', '');
$music = cq_music_diy($input, $input, $input, null, '');
echo $escaped, "\n", $roundtrip, "\n", $share, "\n", $music, "\n";
?>
--EXPECT--
