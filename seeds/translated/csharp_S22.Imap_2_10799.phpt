--TEST--
Translated seed (inferredbugs-csharp: S22.Imap/2)
--FILE--
<?php
$h = fopen('php://temp', 'r+');
fwrite($h, "abc\r\n123{4}\r\n");
rewind($h);
$s = '';
while (!feof($h)) {
    $c = fgetc($h);
    if ($c === "\r") continue;
    if ($c === "\n") {
        if (preg_match('/\{(\d+)\}$/', $s, $m)) {
            $n = (int)$m[1];
            $s = substr($s, 0, -strlen($m[0])) . '"' . fread($h, $n) . '"';
        }
        $s = '';
    } else {
        $s .= $c;
    }
}
fclose($h);
echo "ok\n";
?>
--EXPECT--
