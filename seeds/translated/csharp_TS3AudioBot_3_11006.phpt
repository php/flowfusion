--TEST--
Translated seed (inferredbugs-csharp: TS3AudioBot/3)
--FILE--
<?php
$version = phpversion();
if (preg_match('/(\d+)(?:\.(\d+)){2,3}/', $version, $m)) {
    $parts = explode('.', $m[0]);
    $semver = $parts[0] . '.' . ($parts[1] ?? '0') . '.' . ($parts[2] ?? '0') . (isset($parts[3]) ? '.' . $parts[3] : '');
} else {
    $semver = null;
}
echo ($semver ?? '?') . PHP_EOL;
?>
--EXPECT--
