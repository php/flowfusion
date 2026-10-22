--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/23)
--FILE--
<?php
$bindings = [];
if (!$bindings) {
    $bindings = [null];
}
$out = '';
for ($i = 0; $i < count($bindings); $i++) {
    $member = (object)['Name' => 't0_col' . $i];
    $expr = $bindings[$i];
    $out .= 't0.' . $member->Name . ' = ';
    $out .= is_scalar($expr) || $expr === null ? var_export($expr, true) : json_encode($expr);
    if ($i < count($bindings) - 1) {
        $out .= ",\n";
    }
}
echo $out;
?>
--EXPECT--
