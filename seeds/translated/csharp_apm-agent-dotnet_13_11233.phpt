--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/13)
--FILE--
<?php
$serverUrl = "http://myServer.com:1234";
$u = parse_url($serverUrl);
echo ($u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . '/') . PHP_EOL;

$invalid = "InvalidUrl";
$u = parse_url($invalid);
echo (($u === false || !isset($u['scheme'], $u['host'])) ? "http://localhost:8200/" : ($u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . '/')) . PHP_EOL;

$serverUrl1 = "http://myServer1.com:1234";
$serverUrl2 = "http://myServer2.com:1234";
foreach (explode(',', $serverUrl1 . ',' . $serverUrl2) as $s) {
    $u = parse_url($s);
    if ($u !== false && isset($u['scheme'], $u['host'])) {
        echo ($u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . '/') . PHP_EOL;
    }
}

$serverUrl2 = "invalidUrl";
foreach (explode(',', $serverUrl1 . ',' . $serverUrl2 . ',' . "http://myServer2.com:1234") as $s) {
    $u = parse_url($s);
    if ($u !== false && isset($u['scheme'], $u['host'])) {
        echo ($u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . '/') . PHP_EOL;
    }
}

foreach (["Debug", "Error", "Info", "Warning", "InvalidLogLevel"] as $lvl) {
    $map = ["Debug" => "DEBUG", "Error" => "ERROR", "Info" => "INFO", "Warning" => "WARNING"];
    $resolved = $map[$lvl] ?? "ERROR";
    echo $resolved . PHP_EOL;
}

$serviceName = "Elastic_Apm_Tests";
echo (strpos($serviceName, '.') === false ? "ok" : "bad") . PHP_EOL;
?>
--EXPECT--
