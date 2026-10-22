--TEST--
Translated seed (inferredbugs-csharp: JUST.net/8)
--FILE--
<?php
$input = '{"a":[{"x":1},{"x":2}],"b":{"c":3},"s":"#keep"}';
$transformer = '{"#":["#copy($.a[0],root)","#replace($.b.c,42)","#delete($.s)"],"items":["#currentvalue($.a,root)"]}';

$in = json_decode($input, true);
$tr = json_decode($transformer, true);

if (isset($tr['#']) && is_array($tr['#'])) {
    foreach ($tr['#'] as $op) {
        if (is_string($op) && str_starts_with(trim($op), '#copy(')) {
            $tr['copied'] = $in['a'][0];
        } elseif (is_string($op) && str_starts_with(trim($op), '#replace(')) {
            $tr['b']['c'] = 42;
        } elseif (is_string($op) && str_starts_with(trim($op), '#delete(')) {
            unset($tr['s']);
        }
    }
    unset($tr['#']);
}

$tr['items'] = $in['a'];
echo json_encode($tr, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
