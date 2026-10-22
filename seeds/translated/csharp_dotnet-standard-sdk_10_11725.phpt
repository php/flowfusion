--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/10)
--FILE--
<?php
$e = new Exception('versionDate cannot be null.');
try {
    $v = null;
    if ($v === null || $v === '') {
        throw $e;
    }
} catch (Exception $x) {
}

$s = "héllo";
$b = $s;
$c = $b;
$c .= "!";
$u = mb_convert_encoding($s, 'UTF-8', 'UTF-8');

$a = [];
$a['version'] = '2018-05-01';
$a['text'] = $u;
$a['custom'] = ['x' => 1, 'y' => true];

$f = fopen('php://temp', 'r+');
fwrite($f, $s);
rewind($f);
$g = stream_get_contents($f);
fclose($f);

$m = [];
$m['forced_glossary'] = $g;
$m['parallel_corpus'] = $c;
$m['body'] = json_encode($a, JSON_UNESCAPED_UNICODE);

$r = [];
$r['translate'] = ['version' => $a['version'], 'body' => $m['body']];
$r['identify'] = ['text' => $g];
$r['models'] = ['source' => null, 'target' => 'en', 'default' => false];

echo "";
?>
--EXPECT--
