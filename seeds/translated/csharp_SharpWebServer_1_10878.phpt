--TEST--
Translated seed (inferredbugs-csharp: SharpWebServer/1)
--FILE--
<?php
$mime = [
    '.html' => 'text/html',
    '.htm' => 'text/html',
    '.txt' => 'text/plain',
    '.xml' => 'application/xml',
    '.json' => 'application/json',
    '.css' => 'text/css',
    '.js' => 'application/javascript',
    '.png' => 'image/png',
    '.jpg' => 'image/jpeg',
    '.jpeg' => 'image/jpeg',
    '.gif' => 'image/gif',
    '.svg' => 'image/svg+xml',
    '.pdf' => 'application/pdf',
    '.zip' => 'application/zip',
];
$root = $argv[1] ?? '.';
$path = $argv[2] ?? '';
$ext = strtolower(strrchr($path, '.'));
if ($path === '') {
    $path = 'index.html';
}
$full = rtrim($root, "/\\") . DIRECTORY_SEPARATOR . $path;
if (is_file($full)) {
    $data = file_get_contents($full);
    $type = $mime[$ext] ?? 'application/octet-stream';
    echo $type . "\n";
    echo strlen($data) . "\n";
    echo substr($data, 0, 16) . "\n";
} else {
    $entries = @scandir($root);
    if (is_array($entries)) {
        $out = [];
        foreach ($entries as $e) {
            if ($e === '.' || $e === '..') continue;
            $p = $root . DIRECTORY_SEPARATOR . $e;
            $out[] = [$e, is_dir($p), is_file($p) ? filesize($p) : 0];
        }
        echo json_encode($out, JSON_UNESCAPED_SLASHES) . "\n";
    }
}
?>
--EXPECT--
