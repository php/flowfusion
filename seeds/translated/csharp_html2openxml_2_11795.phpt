--TEST--
Translated seed (inferredbugs-csharp: html2openxml/2)
--FILE--
<?php
$knownExtensions = [
    '.gif' => 'gif', '.bmp' => 'bmp', '.emf' => 'emf', '.ico' => 'ico',
    '.jpeg' => 'jpeg', '.jpg' => 'jpeg', '.jpe' => 'jpeg', '.pcx' => 'pcx',
    '.png' => 'png', '.tiff' => 'tiff', '.wmf' => 'wmf'
];
$knownContentType = [
    'image/gif' => 'gif', 'image/pjpeg' => 'jpeg', 'image/jpg' => 'jpeg',
    'image/jpeg' => 'jpeg', 'image/x-png' => 'png', 'image/png' => 'png',
    'image/tiff' => 'tiff', 'image/vnd.microsoft.icon' => 'ico',
    'image/x-icon' => 'ico', 'image/icon' => 'ico', 'image/ico' => 'ico',
    'text/ico' => 'ico', 'text/application-ico' => 'ico', 'image/bmp' => 'bmp'
];

$cases = [
    'data:image/png;base64,' . base64_encode(""),
    'file:///tmp/a%20b.JPG',
    'http://example.com/image.axd?picture=img1.jpg',
    'http://example.com/noext?x=data:image/gif;base64,AAAA',
    'data:image/vnd.microsoft.icon;base64,AA=='
];

foreach ($cases as $u) {
    $type = null;
    if (str_starts_with($u, 'data:')) {
        if (preg_match('~^data:([^;,]+)~i', $u, $m) && isset($knownContentType[strtolower($m[1])])) $type = $knownContentType[strtolower($m[1])];
    } else {
        $p = parse_url($u);
        $path = $p['path'] ?? '';
        $ext = strtolower(pathinfo(urldecode($path), PATHINFO_EXTENSION));
        if ($ext !== '') $type = $knownExtensions['.' . $ext] ?? null;
        if ($type === null) {
            $ext = strtolower(pathinfo($u, PATHINFO_EXTENSION));
            if ($ext !== '') $type = $knownExtensions['.' . $ext] ?? null;
        }
        if ($type === null && preg_match('~data:([^;,]+)~i', $u, $m) && isset($knownContentType[strtolower($m[1])])) $type = $knownContentType[strtolower($m[1])];
    }
    $raw = str_starts_with($u, 'data:') ? (preg_match('~^data:[^;,]+;base64,(.*)$~i', $u, $m) ? base64_decode($m[1], true) : '') : '';
    $size = strlen($raw);
    echo ($type ?? 'none') . ':' . $size . "\n";
}
?>
--EXPECT--
