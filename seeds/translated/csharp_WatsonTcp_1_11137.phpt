--TEST--
Translated seed (inferredbugs-csharp: WatsonTcp/1)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
if (!$server) exit(1);
$addr = stream_socket_get_name($server, false);
$port = (int)substr(strrchr($addr, ':'), 1);

$pid = pcntl_fork();
if ($pid === 0) {
    usleep(100000);
    $c = stream_socket_client("tcp://127.0.0.1:$port", $e, $es);
    fwrite($c, "5:he");
    usleep(200000);
    fwrite($c, "llo");
    fclose($c);
    exit(0);
}

stream_set_blocking($server, false);
$client = null;
$buf = '';
$deadline = microtime(true) + 2.0;

while (microtime(true) < $deadline) {
    $r = [$server];
    $w = $e = [];
    if (stream_select($r, $w, $e, 0, 100000) > 0) {
        $client = stream_socket_accept($server, 0);
        if ($client) break;
    }
}

if ($client) {
    stream_set_blocking($client, false);
    $data = '';
    $len = null;
    $deadline = microtime(true) + 2.0;
    while (microtime(true) < $deadline) {
        $chunk = fread($client, 1);
        if ($chunk === '' || $chunk === false) {
            usleep(25000);
            continue;
        }
        $buf .= $chunk;
        if ($len === null) {
            $p = strpos($buf, ':');
            if ($p !== false) {
                $len = (int)substr($buf, 0, $p);
                $data = substr($buf, $p + 1);
            }
        } else {
            $data .= $chunk;
        }
        if ($len !== null && strlen($data) >= $len) break;
    }
    if ($len !== null && strlen($data) > $len) $data = substr($data, 0, $len);
    fclose($client);
}

fclose($server);
pcntl_wait($status);
?>
--EXPECT--
