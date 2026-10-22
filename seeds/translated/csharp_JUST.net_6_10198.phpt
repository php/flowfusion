--TEST--
Translated seed (inferredbugs-csharp: JUST.net/6)
--FILE--
<?php
$input = json_decode('{"a":[{"x":1},{"x":2}],"b":{"#":["#copy($.a[0].x)","keep"]},"c":"#"}', true);
$transformer = json_decode('{"a":[{"x":"#concat(#currentvalue($.a,root),\"\",root)"}],"b":{"#":["#copy($.a[0].x)","keep"]},"c":"\\#escaped"}', true);

function walk(&$node, &$root, &$ctx) {
    if (is_array($node)) {
        foreach ($node as $k => &$v) walk($v, $root, $ctx);
        return;
    }
    if (!is_string($node)) return;
    $s = trim($node);
    if ($s === '#') return;
    if ($s !== '' && $s[0] === '#') {
        if (preg_match('/^#copy\((.+)\)$/', $s, $m)) {
            $path = $m[1];
            if ($path === '$.a[0].x') $node = $root['a'][0]['x'];
        } elseif (preg_match('/^#concat\((.*)\)$/', $s, $m)) {
            $node = '';
        } elseif ($s === '#escaped') {
            $node = '#escaped';
        }
    } elseif (strlen($s) > 1 && $s[0] === '\\' && $s[1] === '#') {
        $node = substr($s, 1);
    }
}

$ctx = ['input' => &$input];
walk($transformer, $transformer, $ctx);
echo json_encode($transformer, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
