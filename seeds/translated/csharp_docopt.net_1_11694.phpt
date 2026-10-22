--TEST--
Translated seed (inferredbugs-csharp: docopt.net/1)
--FILE--
<?php
$usage = "Naval Fate.

    Usage:
      naval_fate.exe ship new <name>...
      naval_fate.exe ship <name> move <x> <y> [--speed=<kn>]
      naval_fate.exe ship shoot <x> <y>
      naval_fate.exe mine (set|remove) <x> <y> [--moored | --drifting]
      naval_fate.exe (-h | --help)
      naval_fate.exe --version

    Options:
      -h --help     Show this screen.
      --version     Show version.
      --speed=<kn>  Speed in knots [default: 10].
      --moored      Moored (anchored) mine.
      --drifting    Drifting mine.

    ";

$args = array_slice($argv, 1);
$opts = getopt("h", ["help", "version", "speed::", "moored", "drifting"]);
foreach ($opts as $k => $v) {
    if (is_array($v)) {
        foreach ($v as $x) echo $k . " = " . $x . PHP_EOL;
    } else {
        echo $k . " = " . $v . PHP_EOL;
    }
}
?>
--EXPECT--
