--TEST--
Translated seed (inferredbugs-csharp: Jellyfin.Plugin.PhoenixAdult/4)
--FILE--
<?php
$sites = [
    0 => [
        0 => ['Alpha.Site', 'https://alpha.example/', '/search?q='],
        1 => ['', '', '/fallback'],
    ],
    1 => [
        0 => ['Beta', 'https://beta.example/', ''],
        1 => ['Beta-Plus', '', 'search'],
    ],
];

$abbr = ['JAV' => 'Japanese Adult Video', 'TV' => 'Television'];

$title = "alpha-site 2024 01 02";
$clearName = preg_replace('/\W/u', '', $title);
$best = [null, null];
$bestLen = -1;
foreach ($sites as $si => $site) {
    foreach ($site as $sj => $data) {
        $siteName = $data[0];
        if ($siteName !== '') {
            $clearSite = preg_replace('/\W/u', '', $siteName);
            if (stripos($clearName, $clearSite) === 0 && strlen($clearSite) > $bestLen) {
                $best = [[$si, $sj], $clearSite];
                $bestLen = strlen($clearSite);
            }
        }
    }
}

$searchTitle = $title;
if (preg_match('/\b[0-9]{4} [0-9]{2} [0-9]{2}\b/', $searchTitle, $m)) {
    $dt = DateTime::createFromFormat('Y m d', $m[0], new DateTimeZone('UTC'));
    if ($dt !== false) {
        $searchTitle = trim(preg_replace('/\b[0-9]{4} [0-9]{2} [0-9]{2}\b/', '', $searchTitle));
    }
}

$searchTitle = preg_replace('/[^a-zA-Z0-9 \'"]/u', ' ', $searchTitle);
$searchTitle = preg_replace('/\s+/u', ' ', trim($searchTitle));

$base = $sites[0][0][1];
$search = $sites[0][0][2];
if ($search !== '' && stripos($search, 'http') !== 0) {
    $search = $base . $search;
}

$input = 'SGVsbG8tX3dvcmxk';
$working = strtr($input, '-_', '+/');
while (strlen($working) % 4 !== 0) $working .= '=';
$decoded = base64_decode($working, true);
if ($decoded === false) $decoded = null;

echo $best[1] . "\n";
echo $searchTitle . "\n";
echo $search . "\n";
echo ($decoded ?? '') . "\n";
?>
--EXPECT--
