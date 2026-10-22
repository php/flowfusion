--TEST--
Translated seed (inferredbugs-csharp: SeleniumBasic/1)
--FILE--
<?php
$values = [null, '', '0', 0, false, 'abc', [1, 2], ['x' => 1], new ArrayObject([1, 2, 3])];

foreach ($values as $v) {
    $s = @($v . '');
    $a = is_array($v) || $v instanceof Traversable ? iterator_to_array($v, false) : [$v];
    $r = preg_match('/^(?:0|false|abc)$/i', (string)$s);
    $n = filter_var($s, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    $m = array_merge(['k' => 'v'], is_array($v) ? $v : []);
    $c = count($a);
    $o = json_encode(['v' => $v, 's' => $s, 'r' => $r, 'n' => $n, 'c' => $c, 'm' => $m], JSON_PARTIAL_OUTPUT_ON_ERROR);
    if ($o === false) {
        echo "";
    }
}
?>
--EXPECT--
