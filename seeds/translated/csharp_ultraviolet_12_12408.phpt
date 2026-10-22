--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/12)
--FILE--
<?php
$state = ['op' => '==', 'name' => 'count', 'ref' => '42', 'cache' => null];
$obj = ['count' => '42'];
$type = gettype($obj[$state['name']]);
if ($state['cache'] === null || gettype($state['cache']) !== $type) {
    $state['cache'] = match ($type) {
        'integer' => (int)$state['ref'],
        'double' => (float)$state['ref'],
        'boolean' => filter_var($state['ref'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
        default => $state['ref'],
    };
}
$lhs = $obj[$state['name']];
$rhs = $state['cache'];
$result = match ($state['op']) {
    '==' => $lhs == $rhs,
    '!=' => $lhs != $rhs,
    '<'  => $lhs < $rhs,
    '<=' => $lhs <= $rhs,
    '>'  => $lhs > $rhs,
    '>=' => $lhs >= $rhs,
    default => false,
};
echo $result ? "1" : "0";
?>
--EXPECT--
