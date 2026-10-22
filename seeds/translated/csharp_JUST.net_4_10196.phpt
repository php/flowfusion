--TEST--
Translated seed (inferredbugs-csharp: JUST.net/4)
--FILE--
<?php
$input = json_decode('{"a":[1,2,3],"b":{"x":10,"y":20},"c":"keep"}', true);
$transformer = json_decode('{"#loop($.a[*])":{"v":"#currentvalue()","i":"#currentindex()"},"#ifgroup(#stringequals(#currentvalueatpath($.b.x),10))":{"ok":"yes"},"#":["#copy($.b)","#replace($.c,\"changed\")","#delete($.b.y)"]}', true);

$ctx = ['input' => $input];

$select = function ($data, $path) {
    if ($path === '$.a[*]') return $data['a'];
    if ($path === '$.b') return $data['b'];
    if ($path === '$.b.x') return $data['b']['x'];
    if ($path === '$.c') return $data['c'];
    if ($path === '$.b.y') return $data['b']['y'];
    return null;
};

$eval = function ($expr, $current, $ctx) use (&$select) {
    $expr = trim($expr);
    if ($expr === '#currentvalue()') return $current;
    if ($expr === '#currentindex()') return $current['__i'];
    if (preg_match('/^#currentvalueatpath\((.*)\)$/', $expr, $m)) return $select($ctx['input'], trim($m[1]));
    if (preg_match('/^#stringequals\((.*?),(.*)\)$/', $expr, $m)) return (string)$eval($m[1], $current, $ctx) === trim($m[2], " \t\n\r\0\x0B'\"");
    if (preg_match('/^#ifgroup\((.*)\)$/', $expr, $m)) return (bool)$eval($m[1], $current, $ctx);
    return $expr;
};

$out = [];
foreach ($transformer['#loop($.a[*])'] as $k => $v) {}
$loop = $select($ctx['input'], '$.a[*]');
foreach ($loop as $i => $v) {
    $cur = ['value' => $v, '__i' => $i];
    $out['loop'][] = ['v' => $eval('#currentvalue()', $cur, $ctx), 'i' => $eval('#currentindex()', $cur, $ctx)];
}
if ($eval('#ifgroup(#stringequals(#currentvalueatpath($.b.x),10))', ['__i' => 0], $ctx)) {
    $out['ok'] = 'yes';
}
$out['copy'] = $select($ctx['input'], '$.b');
$out['c'] = 'changed';
unset($input['b']['y']);
echo json_encode($out, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
