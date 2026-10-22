--TEST--
Translated seed (inferredbugs-csharp: retina/2)
--FILE--
<?php
$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];

if ($argc !== 2) {
    fwrite(STDOUT, "Usage: Retina.php pattern.rgx\n");
    exit(0);
}

$input = stream_get_contents(STDIN);
$pattern = file_get_contents($argv[1]);
$parts = explode('`', $pattern);
$options = '';
if (count($parts) > 1) {
    $options = array_shift($parts);
}
$regex = '~' . implode('`', $parts) . '~' . strtr($options, ['c' => 'u', 'i' => 'i', 'm' => 'm', 's' => 's', 'x' => 'x']);
$mode = 'M';
for ($i = 0, $n = strlen($options); $i < $n; $i++) {
    if ($options[$i] === 'G') $mode = 'G';
    elseif ($options[$i] === 'A') $mode = 'A';
}

if ($mode === 'M') {
    preg_match_all($regex, $input, $m);
    fwrite(STDOUT, (string)count($m[0]) . PHP_EOL);
} else {
    $lines = preg_split("/\r\n|\n|\r/", $input);
    foreach ($lines as $line) {
        if ($line === '' && $input === '') continue;
        $ok = preg_match($regex, $line) === 1;
        if (($mode === 'G') xor ($mode === 'A' && $ok)) {
            if ($ok || $mode === 'G') fwrite(STDOUT, $line . PHP_EOL);
        }
    }
}
?>
--EXPECT--
