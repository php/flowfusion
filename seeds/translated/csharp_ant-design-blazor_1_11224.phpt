--TEST--
Translated seed (inferredbugs-csharp: ant-design-blazor/1)
--FILE--
<?php
$services = [];

if (!isset($services['HttpClient'])) {
    $services['HttpClient'] = new stdClass();
}

if (!isset($services['JsInterop'])) {
    $services['JsInterop'] = new stdClass();
}

echo "";
?>
--EXPECT--
