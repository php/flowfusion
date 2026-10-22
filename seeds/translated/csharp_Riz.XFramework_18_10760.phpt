--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/18)
--FILE--
<?php
$bindings = [
    ['member' => 'Id', 'expression' => 1],
    ['member' => 'Name', 'expression' => 'Alice'],
    ['member' => 'Count', 'expression' => 0],
];

if (!$bindings || count($bindings) === 0) {
    exit(0);
}

$out = '';
$last = count($bindings) - 1;

for ($i = 0; $i < count($bindings); ++$i) {
    $m = $bindings[$i];
    $out .= $m['member'] . ' = ';

    if (is_scalar($m['expression']) || $m['expression'] === null) {
        $v = $m['expression'];
        if (is_string($v)) {
            $out .= "'" . str_replace("'", "''", $v) . "'";
        } elseif (is_bool($v)) {
            $out .= $v ? '1' : '0';
        } elseif ($v === null) {
            $out .= 'NULL';
        } else {
            $out .= (string)$v;
        }
    } else {
        $out .= json_encode($m['expression']);
    }

    if ($i < $last) {
        $out .= ",\n";
    }
}

echo $out;
?>
--EXPECT--
