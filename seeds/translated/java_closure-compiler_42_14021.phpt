--TEST--
Translated seed (inferredbugs-java: closure-compiler/42)
--FILE--
<?php
$shouldFavorCommaOverSemiColon = false;
$favorsCommaOverSemiColon = $shouldFavorCommaOverSemiColon;

function canFuseIntoOneStatement($block, $favorsCommaOverSemiColon) {
    if (!$favorsCommaOverSemiColon && !is_array($block)) {
        return false;
    }
    if (count($block) <= 1) {
        return false;
    }
    $last = end($block);
    foreach ($block as $key => $statement) {
        if ($key !== count($block) - 1 && !is_expr_result($statement)) {
            return false;
        }
    }
    return is_control_statement($last);
}

function is_expr_result($statement) {
    return true; // Assuming all statements are expression results for simplification
}

function is_control_statement($statement) {
    return true; // Assuming the last statement is a control statement for simplification
}

function fuseIntoOneStatement(&$parent, $first, $last) {
    if ($first === $last) {
        return $first;
    }
    $commaTree = $parent[$first];
    for ($i = $first + 1; $i <= $last; $i++) {
        $commaTree = fuseExpressionIntoExpression($commaTree, $parent[$i]);
        unset($parent[$i]);
    }
    $parent[$first] = $commaTree;
    return $commaTree;
}

function fuseExpressionIntoExpression($exp1, $exp2) {
    return "$exp1, $exp2"; // Concatenating strings to simulate the fusion
}

$block = ['a = 1;', 'b = 2;', 'c = 3;'];
if (canFuseIntoOneStatement($block, $favorsCommaOverSemiColon)) {
    fuseIntoOneStatement($block, 0, count($block) - 1);
}
--EXPECT--
