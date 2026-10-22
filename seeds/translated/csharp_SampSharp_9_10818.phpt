--TEST--
Translated seed (inferredbugs-csharp: SampSharp/9)
--FILE--
<?php
$argv = $_SERVER['argv'] ?? [];
$hosted = false;
$redirect = false;
$pipe = null;
$unix = null;
$tcpHost = '127.0.0.1';
$tcpPort = 8888;
for ($i = 1, $n = count($argv); $i < $n; $i++) {
    $a = $argv[$i];
    if ($a === '-h' || $a === '--hosted') {
        $hosted = true;
    } elseif ($a === '-r' || $a === '--redirect-console-output') {
        $redirect = true;
    } elseif ($a === '-p' || $a === '--pipe') {
        $v = $argv[$i + 1] ?? null;
        if ($v !== null && $v !== '' && $v[0] !== '-') {
            $pipe = $v;
            $i++;
        } else {
            $pipe = 'SampSharp';
        }
    } elseif ($a === '-u' || $a === '--unix') {
        $v = $argv[$i + 1] ?? null;
        if ($v !== null && $v !== '' && $v[0] !== '-') {
            $unix = $v;
            $i++;
        } else {
            $unix = '/tmp/SampSharp';
        }
    } elseif ($a === '--tcp') {
        $v = $argv[$i + 1] ?? null;
        if ($v !== null && $v !== '' && $v[0] !== '-') {
            $pos = strrpos($v, ':');
            if ($pos === false) {
                $tcpPort = (int)$v;
            } else {
                $tcpHost = substr($v, 0, $pos);
                $tcpPort = (int)substr($v, $pos + 1);
            }
            $i++;
        }
    }
}
if (!$hosted && $pipe === null && $unix === null) {
    if (stripos(PHP_OS, 'WIN') === 0) {
        $pipe = 'SampSharp';
    } else {
        $unix = '/tmp/SampSharp';
    }
}
$client = $hosted ? 'hosted' : ($pipe !== null ? "pipe:$pipe" : ($unix !== null ? "unix:$unix" : "tcp:$tcpHost:$tcpPort"));
if ($redirect) {
    $out = fopen('php://stdout', 'w');
    fwrite($out, $client . "\n");
    fclose($out);
} else {
    echo $client, "\n";
}
--EXPECT--
