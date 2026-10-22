--TEST--
Translated seed (inferredbugs-csharp: JUST.net/7)
--FILE--
<?php
$input = json_decode('{"a":[1,2],"b":{"c":3},"s":"x"}', true);
$transformer = json_decode('{"#":["#copy($.a[0])","#replace($.b.c,4)","#delete($.s)"],"keep":"\\#literal"}', true);

foreach ($transformer as $k => $v) {
    if ($k === '#') {
        foreach ($v as $op) {
            if (is_string($op) && str_starts_with(trim($op), '#copy(')) {
                if (preg_match('/^#copy\((.+)\)$/', trim($op), $m)) {
                    $path = $m[1];
                    if ($path === '$.a[0]') {
                        $transformer['copied'] = $input['a'][0];
                    }
                }
            } elseif (is_string($op) && str_starts_with(trim($op), '#replace(')) {
                if (preg_match('/^#replace\((.+?),(.+)\)$/', trim($op), $m)) {
                    if (trim($m[1]) === '$.b.c') {
                        $input['b']['c'] = json_decode(trim($m[2]), true);
                    }
                }
            } elseif (is_string($op) && str_starts_with(trim($op), '#delete(')) {
                if (preg_match('/^#delete\((.+)\)$/', trim($op), $m)) {
                    if (trim($m[1]) === '$.s') {
                        unset($input['s']);
                    }
                }
            }
        }
        unset($transformer['#']);
    }
}

echo json_encode(['input' => $input, 'transformer' => $transformer], JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
