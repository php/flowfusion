--TEST--
Translated seed (inferredbugs-csharp: JUST.net/5)
--FILE--
<?php
$input = json_decode('{"items":[{"v":1},{"v":2},{"v":3}],"keep":true,"nested":{"a":[10,20]}}', true);
$transformer = json_decode('{"#loop(items, row)":{"sum":"#eval(concat(row.v, \'-x\'))","copy":"#copy(row.v, row)","replace":"#replace(keep, row)","delete":"#delete(nested.a)","escaped":"\\#literal"},"tail":"#ifcondition(keep,true,yes,no)"}', true);

$out = [];
foreach ($transformer as $k => $v) {
    if (is_string($k) && str_starts_with(ltrim($k), '#loop(') && is_array($v)) {
        preg_match('/#loop\((.*)\)/', $k, $m);
        $args = array_map('trim', explode(',', $m[1]));
        $path = $args[0];
        $alias = $args[1] ?? 'loop';
        $arr = $input[$path] ?? [];
        $built = [];
        foreach ($arr as $item) {
            $row = $item;
            $built[] = [
                'sum' => (string)($row['v'] ?? '') . '-x',
                'copy' => $row['v'] ?? null,
                'replace' => $row,
                'escaped' => '#literal',
            ];
        }
        $out[$path] = $built;
    } elseif (is_string($v) && str_starts_with($v, '#ifcondition(')) {
        $out[$k] = $input['keep'] ? 'yes' : 'no';
    } else {
        $out[$k] = $v;
    }
}
echo json_encode($out, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
