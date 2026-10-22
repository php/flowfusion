--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/31)
--FILE--
<?php
$visitedStack = [];
$notOperands = [];
$builder = '';

$mode = $argv[1] ?? '1';

if ($mode === '1') {
    $visitedStack[] = 'x';
    $visitedQty = count($visitedStack);
    $router = 'MethodCall';
    $m = (object)['Method' => (object)['Name' => 'Contains']];
    if (count($visitedStack) != $visitedQty) {
        $b = $router != 'Unary';
        if ($b && $router === 'MethodCall') $b = in_array($m->Method->Name, ['ToString','Contains','StartsWith','EndsWith','IsNullOrEmpty','IndexOf','IsLeapYear'], true);
        if ($b) array_splice($visitedStack, count($visitedStack) - $visitedQty, 1);
    }
    echo count($visitedStack);
} elseif ($mode === '2') {
    $notOperands = [];
    $node = (object)['NodeType' => 'Not', 'Operand' => (object)['NodeType' => 'Call']];
    $count = count($notOperands);
    $isNotMethod = $node->NodeType === 'Not' && $node->Operand->NodeType === 'Call';
    if ($isNotMethod) $notOperands[] = $node->Operand;
    if (count($notOperands) > $count) {
        $qty = 0;
        for ($i = count($notOperands) - 1; $i >= 0; $i--) {
            array_splice($notOperands, $i, 1);
            $qty += 1;
            if (count($notOperands) - $count <= $qty) break;
        }
    }
    echo count($notOperands);
} else {
    $left = null;
    $right = 'TAN';
    $leftNodeType = 'Constant';
    $left = $leftNodeType === 'Constant' ? $right : $left;
    $right = $leftNodeType === 'Constant' ? null : $right;
    $builder .= 'ISNULL(' . $left . ',' . $right . ')';
    echo $builder;
}
--EXPECT--
