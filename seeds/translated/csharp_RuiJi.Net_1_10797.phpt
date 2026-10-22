--TEST--
Translated seed (inferredbugs-csharp: RuiJi.Net/1)
--FILE--
<?php
$results = [];
$code = <<<'PHP'
$results[] = "ok";
PHP;
eval($code);
foreach ($results as $v) {
    echo $v, PHP_EOL;
}
?>
--EXPECT--
