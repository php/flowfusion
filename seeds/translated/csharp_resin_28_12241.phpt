--TEST--
Translated seed (inferredbugs-csharp: resin/28)
--FILE--
<?php
$base = getcwd() . DIRECTORY_SEPARATOR . 'App_Data';
$parts = ['.', '..', 'App_Data', '', 'subdir', '..', 'file.txt'];
$path = $base;
foreach ($parts as $p) {
    if ($p === '' || $p === '.') {
        continue;
    }
    if ($p === '..') {
        $path = dirname($path);
    } else {
        $path .= DIRECTORY_SEPARATOR . $p;
    }
}

$query = 'a=1&b=true&c=0&empty=&flag&x=01&y=False&z=TRUE';
parse_str($query, $parsed);

$tokens = preg_split('/[^A-Za-z0-9]+/', 'LatinTokenizer 123 -- edge_case__test', -1, PREG_SPLIT_NO_EMPTY);

$store = [
    'path' => $path,
    'tokens' => $tokens,
    'config' => $parsed,
];

$store['query'] = http_build_query($parsed);
$store['path2'] = realpath($base) ?: $base;

echo '';
?>
--EXPECT--
