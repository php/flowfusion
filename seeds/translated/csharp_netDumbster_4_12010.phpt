--TEST--
Translated seed (inferredbugs-csharp: netDumbster/4)
--FILE--
<?php
$port = random_int(20000, 60000);
$server = stream_socket_server("tcp://127.0.0.1:$port", $errno, $errstr);
if ($server === false) {
    exit(1);
}
stream_set_blocking($server, false);

for ($i = 0; $i < 2; $i++) {
    $client = stream_socket_client("tcp://127.0.0.1:$port", $errno, $errstr, 1);
    if ($client === false) {
        fclose($server);
        exit(1);
    }
    fwrite($client, "hello $i\n");
    fclose($client);

    $conn = false;
    for ($j = 0; $j < 100; $j++) {
        $conn = @stream_socket_accept($server, 0);
        if ($conn !== false) {
            break;
        }
        usleep(10000);
    }
    if ($conn === false) {
        fclose($server);
        exit(1);
    }

    $data = '';
    while (!feof($conn)) {
        $chunk = fread($conn, 8192);
        if ($chunk === '' || $chunk === false) {
            break;
        }
        $data .= $chunk;
    }
    fclose($conn);

    if (trim($data) !== "hello $i") {
        fclose($server);
        exit(1);
    }
}

fclose($server);
?>
--EXPECT--
