--TEST--
Translated seed (inferredbugs-csharp: kiota/12)
--FILE--
<?php
$urlTplParams = ['baseurl' => 'https://example.test'];
$position = 42;
$urlTplParams['id'] = (string)$position;
echo $urlTplParams['baseurl'] . '/' . $urlTplParams['id'] . PHP_EOL;
?>
--EXPECT--
