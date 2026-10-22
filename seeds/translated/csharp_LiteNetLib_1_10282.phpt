--TEST--
Translated seed (inferredbugs-csharp: LiteNetLib/1)
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:9050', $errno, $errstr);
if (!$server) exit(1);
stream_set_blocking($server, false);

$client = stream_socket_client('tcp://127.0.0.1:9050', $errno, $errstr, 1);
if (!$client) exit(1);
stream_set_blocking($client, false);

fwrite($client, "HELLO! ПРИВЕТ!");

$accepted = null;
$received = 0;
$sent = 0;
$buf = '';
$start = microtime(true);

while (microtime(true) - $start < 2.0) {
    if (!$accepted) {
        $accepted = @stream_socket_accept($server, 0);
        if ($accepted) stream_set_blocking($accepted, false);
    }

    if ($accepted) {
        $data = @fread($accepted, 8192);
        if ($data !== false && $data !== '') {
            $buf .= $data;
            while (strlen($buf) >= 4) {
                $n = unpack('N', substr($buf, 0, 4))[1];
                if (strlen($buf) < 4 + $n) break;
                $msg = substr($buf, 4, $n);
                $buf = substr($buf, 4 + $n);
                $received++;
                if ($received === 1) {
                    for ($i = 0; $i < 10000; $i++) {
                        fwrite($client, pack('N', $i + 1) . str_repeat("\0", 1296));
                        $sent++;
                    }
                }
            }
        }
    }

    $data = @fread($client, 8192);
    if ($data !== false && $data !== '') {
        $buf2 = $data;
        while (strlen($buf2) >= 4) {
            $dt = unpack('N', substr($buf2, 0, 4))[1];
            $buf2 = substr($buf2, 4 + 1296);
            $received++;
        }
    }

    usleep(10000);
}

if ($accepted) fclose($accepted);
fclose($client);
fclose($server);
?>
--EXPECT--
