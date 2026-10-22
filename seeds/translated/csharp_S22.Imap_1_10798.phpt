--TEST--
Translated seed (inferredbugs-csharp: S22.Imap/1)
--FILE--
<?php
$fp = fopen('php://temp', 'r+');
fwrite($fp, "* OK {5}\r\nhello\r\n* CAPABILITY IMAP4rev1 IDLE QUOTA\r\nxm001 OK done\r\n");
rewind($fp);

$buf = '';
while (($c = fgetc($fp)) !== false) {
    if ($c === "\r") continue;
    if ($c === "\n") {
        if (preg_match('/\{(\d+)\}$/', $buf, $m)) {
            $n = (int)$m[1];
            $data = '';
            while ($n > 0 && !feof($fp)) {
                $chunk = fread($fp, $n);
                if ($chunk === false || $chunk === '') break;
                $data .= $chunk;
                $n -= strlen($chunk);
            }
            $buf = substr($buf, 0, -strlen($m[0])) . '"' . $data . '"';
        }
        $buf = '';
        continue;
    }
    $buf .= $c;
}

rewind($fp);
$all = stream_get_contents($fp);
if (strpos($all, 'IDLE') === false && strpos($all, 'QUOTA') !== false) {
    $caps = [];
    foreach (explode(' ', trim(substr("* CAPABILITY IMAP4rev1 IDLE QUOTA", 13))) as $s) {
        $caps[] = strtoupper($s);
    }
    $ok = in_array('IDLE', $caps, true) && in_array('QUOTA', $caps, true);
}
fclose($fp);
?>
--EXPECT--
