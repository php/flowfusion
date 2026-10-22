--TEST--
Translated seed (inferredbugs-csharp: retina/1)
--FILE--
<?php
if ($argc === 2) {
    $input = stream_get_contents(STDIN);
    $pattern = file_get_contents($argv[1]);
    $lines = explode("\n", $pattern);
    if (count($lines) > 1) {
        array_shift($lines);
    }
    $regex = '/' . str_replace('/', '\/', implode("\n", $lines)) . '/';
    preg_match_all($regex, $input, $m);
    echo count($m[0]), "\n";
} else {
    echo "Usage: Retina.php pattern.rgx\n";
}
?>
--EXPECT--
