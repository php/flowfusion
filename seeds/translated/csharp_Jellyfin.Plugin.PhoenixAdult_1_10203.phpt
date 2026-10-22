--TEST--
Translated seed (inferredbugs-csharp: Jellyfin.Plugin.PhoenixAdult/1)
--FILE--
<?php
$names = ["  a &amp; b  ", "x", "y", "z"];
$items = [];
foreach ($names as $i => $n) {
    $items[] = [
        'Name' => html_entity_decode($n, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        'IndexNumber' => $i % 2 ? $i : null,
        'PremiereDate' => $i === 2 ? new DateTimeImmutable("2020-01-0" . ($i + 1)) : null,
    ];
}
usort($items, function ($a, $b) {
    $ai = $a['IndexNumber'] !== null;
    $bi = $b['IndexNumber'] !== null;
    if ($ai !== $bi) return $bi <=> $ai;
    if ($ai && $bi) return $b['IndexNumber'] <=> $a['IndexNumber'];
    if ($a['PremiereDate'] && $b['PremiereDate']) return abs($a['PremiereDate']->getTimestamp() - time()) <=> abs($b['PremiereDate']->getTimestamp() - time());
    return 100 - levenshtein(mb_strtolower("abc"), mb_strtolower($a['Name']));
});
foreach ($items as &$it) {
    $it['Name'] = trim($it['Name']);
}
unset($it);
echo count($items), PHP_EOL;
?>
--EXPECT--
