--TEST--
Translated seed (inferredbugs-csharp: JUST.net/3)
--FILE--
<?php
$input = json_decode('{"a":[{"x":1},{"x":2}],"b":{"c":3},"s":"#keep"}', true);
$transformer = json_decode('{"#loop($.a[*])":{"y":"#currentvalue()","z":"#ifcondition(#currentindex(),0,#concat(first,second),#concat(other,wise))"},"#":["#copy($.b.c)","#replace($.b.c,4)","#delete($.s)"],"t":"\\#literal"}', true);

if (isset($transformer['#']) && is_array($transformer['#'])) {
    foreach ($transformer['#'] as $op) {
        if (is_string($op) && strlen($op) && $op[0] === '#') {
            if (preg_match('/^#copy\((.*)\)$/', $op, $m)) {
                $path = trim($m[1]);
                if ($path === '$.b.c') {
                    $copied = $input['b']['c'] ?? null;
                    $transformer['copied'] = $copied;
                }
            } elseif (preg_match('/^#replace\((.*?),(.*)\)$/', $op, $m)) {
                $key = trim($m[1]);
                $val = trim($m[2]);
                if ($key === '$.b.c') {
                    $input['b']['c'] = (int)$val;
                }
            } elseif (preg_match('/^#delete\((.*)\)$/', $op, $m)) {
                $path = trim($m[1]);
                if ($path === '$.s') {
                    unset($input['s']);
                }
            }
        }
    }
}

if (isset($transformer['#loop($.a[*])']) && is_array($input['a'])) {
    $out = [];
    foreach ($input['a'] as $i => $item) {
        $out[] = [
            'y' => $item,
            'z' => $i === 0 ? 'firstsecond' : 'otherwise',
        ];
    }
    $transformer['a'] = $out;
    unset($transformer['#loop($.a[*])']);
}

$transformer['t'] = ltrim($transformer['t'], '\\');
echo json_encode([$input, $transformer], JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
