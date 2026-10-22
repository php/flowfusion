--TEST--
Translated seed (inferredbugs-csharp: Jellyfin.Plugin.PhoenixAdult/3)
--FILE--
<?php
$sites = [
    0 => [
        0 => ['JAV', 'https://example.com/', '/search?q='],
        1 => ['JAV', '', 'search/'],
    ],
];
$abbr = ['JAV ' => 'Japanese Adult Video '];
$siteIdList = [7 => 'JAV'];

$title = 'JAV-123 2024 01 02';
$clearName = preg_replace('/\W/u', '', $title);
$possible = [];
foreach ($sites as $k => $site) {
    foreach ($site as $j => $data) {
        $clearSite = preg_replace('/\W/u', '', $data[0]);
        if (stripos($clearName, $clearSite) === 0) {
            $possible[] = [$k, $j, strlen($clearSite)];
        }
    }
}
usort($possible, fn($a, $b) => $b[2] <=> $a[2]);
$siteNum = $possible ? [$possible[0][0], $possible[0][1]] : null;

if ($siteNum !== null) {
    $url = $sites[$siteNum[0]][$siteNum[1]][2] ?? $sites[$siteNum[0]][0][2];
    if ($url !== '' && stripos($url, 'http') !== 0) {
        $base = $sites[$siteNum[0]][$siteNum[1]][1] !== '' ? $sites[$siteNum[0]][$siteNum[1]][1] : $sites[$siteNum[0]][0][1];
        $url = $base . $url;
    }
}

$newTitle = $title . ' ';
foreach ($abbr as $k => $v) {
    if (stripos($newTitle, $k) !== false) {
        $newTitle = preg_replace('/' . preg_quote($k, '/') . '/i', $v, $newTitle, 1);
        break;
    }
}
$newTitle = trim($newTitle);

$searchTitle = 'ABC 2024 01 02';
if (preg_match('/\b[0-9]{4} [0-9]{2} [0-9]{2}\b/', $searchTitle, $m)) {
    $dt = DateTimeImmutable::createFromFormat('Y m d', $m[0], new DateTimeZone('UTC'));
    if ($dt !== false) {
        $searchTitle = trim(preg_replace('/\b[0-9]{4} [0-9]{2} [0-9]{2}\b/', '', $searchTitle));
    }
}

$input = 'SGVsbG8td29ybGR_';
if (trim($input) !== '') {
    $working = strtr($input, '-_', '+/');
    while (strlen($working) % 4 !== 0) {
        $working .= '=';
    }
    $decoded = base64_decode($working, true);
    if ($decoded === false) {
        $decoded = null;
    }
}

$siteID = 7;
$providerName = $siteIdList[$siteID] ?? null;
if ($providerName !== null) {
    $class = 'Sites\\' . $providerName;
}

echo "ok\n";
?>
--EXPECT--
