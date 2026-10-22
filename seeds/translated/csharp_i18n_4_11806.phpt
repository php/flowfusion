--TEST--
Translated seed (inferredbugs-csharp: i18n/4)
--FILE--
<?php
$prop = null;
$factory = function () {
    return new stdClass();
};

if ($prop !== null) {
    $x = $prop;
} else {
    if ($prop !== null) {
        $x = $prop;
    } else {
        $prop = $factory();
        $x = $prop;
    }
}

$prop = null;
$prop = $x;
$y = $prop !== null ? $prop : $factory();
echo "ok\n";
?>
--EXPECT--
