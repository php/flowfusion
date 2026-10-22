--TEST--
Translated seed (inferredbugs-csharp: sendgrid-csharp/1)
--FILE--
<?php
$foo = "foo";
$bar = ["header" => $foo];
$bar["enable"] = $bar["header"];
$baz = ["sendgrid" => $bar];
echo $baz["sendgrid"]["enable"];
?>
--EXPECT--
