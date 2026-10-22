--TEST--
Translated seed (inferredbugs-csharp: resin/25)
--FILE--
<?php
$dir = "c:\\temp\\resin_data\\pg";
$ps = null;
$port = 0;

for ($i = 1; $i < $argc; $i++) {
    if ($argv[$i] === "--ps" && isset($argv[$i + 1])) {
        $ps = $argv[++$i];
    } elseif ($argv[$i] === "--port" && isset($argv[$i + 1])) {
        $port = (int)$argv[++$i];
    } elseif ($argv[$i] === "--dir" && isset($argv[$i + 1])) {
        $dir = $argv[++$i];
    }
}

$bin = getcwd();
chdir($dir);

if ($ps === null) {
    $searcher = [$dir];
} else {
    $searcher = [$dir, $ps, $port];
}

$cwd = getcwd();
if ($cwd !== $dir) {
    chdir($bin);
    chdir($dir);
}

unset($searcher);
?>
--EXPECT--
