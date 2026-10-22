--TEST--
Translated seed (inferredbugs-csharp: abp-next-admin/1)
--FILE--
<?php
$locks = [];

$jobKey = "job-1";
if ($jobKey === '' || trim($jobKey) === '') {
    exit(1);
}

if (!array_key_exists($jobKey, $locks)) {
    $locks[$jobKey] = fopen('php://memory', 'r');
}

if (array_key_exists($jobKey, $locks)) {
    fclose($locks[$jobKey]);
    unset($locks[$jobKey]);
}

if ($jobKey === '' || trim($jobKey) === '') {
    exit(1);
}

if (!array_key_exists($jobKey, $locks)) {
    $locks[$jobKey] = fopen('php://memory', 'r');
}

if (array_key_exists($jobKey, $locks)) {
    fclose($locks[$jobKey]);
    unset($locks[$jobKey]);
}
?>
--EXPECT--
