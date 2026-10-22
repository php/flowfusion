--TEST--
Translated seed (inferredbugs-csharp: scriptsharp/1)
--FILE--
<?php
$baseUri = 'http://localhost/';
$contentRoots = $argv;
array_shift($contentRoots);
$registeredContent = [];
$registeredContent['index.html'] = ["<html></html>", "text/html"];
$registeredContent['script.js'] = ["alert(1);", "text/javascript"];

$paths = [
    '/index.html',
    '/script.js',
    '/log/success',
    '/log/failure',
    '/../index.html',
    '/missing.txt',
];

foreach ($paths as $path) {
    $u = parse_url($baseUri . ltrim($path, '/'));
    $cleaned = isset($u['path']) ? $u['path'] : '/';
    $urlPath = ltrim($cleaned, '/');

    if (isset($registeredContent[$urlPath])) {
        $content = $registeredContent[$urlPath];
        $data = $content[0];
        $type = $content[1];
        continue;
    }

    $handled = false;
    foreach ($contentRoots as $root) {
        $possiblePath = rtrim($root, "/\\") . DIRECTORY_SEPARATOR . $urlPath;
        if (is_file($possiblePath)) {
            $ext = strtolower(pathinfo($possiblePath, PATHINFO_EXTENSION));
            if ($ext === 'htm' || $ext === 'html') {
                $type = 'text/html';
            } elseif ($ext === 'js') {
                $type = 'text/javascript';
            } elseif ($ext === 'css') {
                $type = 'text/css';
            } elseif ($ext === 'png') {
                $type = 'image/png';
            } else {
                $type = 'text/plain';
            }
            $data = file_get_contents($possiblePath);
            $handled = true;
            break;
        }
    }

    if (!$handled) {
        if ($path === '/log/success' || $path === '/log/failure') {
            $log = '';
            $status = 204;
        } else {
            $status = 404;
        }
    }
}
?>
--EXPECT--
