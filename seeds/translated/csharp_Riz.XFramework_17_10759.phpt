--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/17)
--FILE--
<?php
$bindings = [
    ['member' => 'Id', 'expr' => 1, 'canEvaluate' => true],
    ['member' => 'Name', 'expr' => 'abc', 'canEvaluate' => true],
    ['member' => 'UpdatedAt', 'expr' => date('c'), 'canEvaluate' => true],
];

if (count($bindings) === 0) {
    throw new Exception('Update action must assign at least one member.');
}

$out = '';
$last = count($bindings) - 1;
for ($i = 0; $i < count($bindings); $i++) {
    $member = $bindings[$i];
    $out .= $member['member'] . ' = ';
    if ($member['canEvaluate']) {
        $v = $member['expr'];
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
        $out .= $member['member'];
    }
    if ($i < $last) {
        $out .= ",\n";
    }
}

echo $out;
?>
--EXPECT--
