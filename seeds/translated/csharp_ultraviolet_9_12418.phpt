--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/9)
--FILE--
<?php
$values = ["", "0", "  ", "event", "event.name", "a:b"];
$map = [];
foreach ($values as $v) {
    $trim = trim($v);
    if ($trim === '') {
        continue;
    }
    $parts = explode(':', $trim, 2);
    $owner = $parts[0];
    $name = $parts[1] ?? $parts[0];
    $key = $owner . '|' . $name;
    $map[$key] = true;
}
foreach ($values as $v) {
    $trim = trim($v);
    if ($trim === '') {
        continue;
    }
    $parts = explode(':', $trim, 2);
    $owner = $parts[0];
    $name = $parts[1] ?? $parts[0];
    $key = $owner . '|' . $name;
    if (!isset($map[$key])) {
        throw new RuntimeException("missing");
    }
    unset($map[$key]);
}
echo "ok\n";
?>
--EXPECT--
