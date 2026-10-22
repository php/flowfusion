--TEST--
Translated seed (inferredbugs-java: Kayak/14)
--FILE--
<?php
$host = '127.0.0.1';
$port = 12345;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
$connected = @socket_connect($socket, $host, $port);

if (!$connected) {
    die("Could not connect to socket\n");
}

$greeting = socket_read($socket, 2048);
if ($greeting !== "< hi >") {
    echo "Did not receive greeting from host.\n";
}

socket_write($socket, "< open busName >", strlen("< open busName >"));
$response = socket_read($socket, 2048);
if ($response !== "< ok >") {
    echo "Could not open bus\n";
}
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 0, "usec" => 100000));

$subscribeId = 1;
$sec = 0;
$usec = 100;

socket_write($socket, "< subscribe $sec $usec " . dechex($subscribeId) . " >", strlen("< subscribe $sec $usec " . dechex($subscribeId) . " >"));

while (true) {
    $frame = @socket_read($socket, 2048);
    if ($frame === false) {
        continue;
    }

    $fields = preg_split('/\s+/', trim($frame));
    
    if (isset($fields[1]) && $fields[1] == "frame") {
        $data = '';
        for ($i = 4; $i < count($fields) - 1; $i++) {
            $data .= $fields[$i];
        }
        $identifier = hexdec($fields[2]);
        $length = intval($fields[3]);
        // Process frame data...
    } elseif (isset($fields[1]) && $fields[1] == "error") {
        echo "Received error from socketcand: $frame\n";
    }
}

socket_close($socket);
--EXPECT--
