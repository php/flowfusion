--TEST--
Translated seed (inferredbugs-csharp: netDumbster/2)
--FILE--
<?php
$port = 2525;
$s = stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr);
if (!$s) {
    exit(1);
}
stream_socket_set_blocking($s, false);

for ($i = 0; $i < 2; $i++) {
    $c = @stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1);
    if ($c) {
        fwrite($c, "msg $i\n");
        fclose($c);
    }
    $r = [$s];
    $w = $e = [];
    if (stream_select($r, $w, $e, 0, 200000) > 0) {
        $conn = @stream_socket_accept($s, 0);
        if ($conn) {
            $data = '';
            while (!feof($conn)) {
                $data .= fgets($conn);
            }
            fclose($conn);
        }
    }
}

fclose($s);

$s = stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr);
if (!$s) {
    exit(1);
}
stream_socket_set_blocking($s, false);

$c = @stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1);
if ($c) {
    fwrite($c, "reused\n");
    fclose($c);
}
$r = [$s];
$w = $e = [];
if (stream_select($r, $w, $e, 0, 200000) > 0) {
    $conn = @stream_socket_accept($s, 0);
    if ($conn) {
        $data = '';
        while (!feof($conn)) {
            $data .= fgets($conn);
        }
        fclose($conn);
    }
}

fclose($s);
?>
--EXPECT--
