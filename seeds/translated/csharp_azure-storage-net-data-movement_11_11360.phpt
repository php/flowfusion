--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/11)
--FILE--
<?php
$src = ['a', 'b'];
$it = new ArrayIterator($src);
$it->rewind();
while ($it->valid()) {
    $v = $it->current();
    if (is_string($v)) {
        $copy = $v;
        $copy .= 'x';
    }
    $it->next();
}

$a = ['k' => 1];
$b = $a;
$b['k'] = 2;
$c = ['nested' => ['x' => 1]];
$d = $c;
$d['nested']['x'] = 3;

$s = "0123456789";
for ($i = 0; $i < 3; $i++) {
    $p = substr($s, $i, 2);
    $q = $p . $i;
    strlen($q);
}

$tokens = [null, '', '0', 'token'];
foreach ($tokens as $t) {
    $u = ['token' => $t];
    if ($u['token'] === null) {
        $u['token'] = 'next';
    }
}

$big = str_repeat('x', 9000);
if (strlen($big) > 8192) {
    $k = sha1($big, false);
    $m = ['hash' => $k];
    $m['hash'] = strtoupper($m['hash']);
}

echo "ok\n";
?>
--EXPECT--
