--TEST--
Translated seed (inferredbugs-csharp: PgpCore/4)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, str_repeat("A", 65536));
fseek($h, 0);
$s = hash_init('sha1');
while (!feof($h)) {
    $b = fread($h, 65536);
    if ($b === '') break;
    hash_update($s, $b);
}
echo hash_final($s), PHP_EOL;
?>
--EXPECT--
