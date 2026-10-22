--TEST--
Translated seed (inferredbugs-java: NettyGameServer/1)
--FILE--
<?php
$data = "博主邮箱:zou90512@126.com";
$bytes = mb_convert_encoding($data, 'UTF-8');
$targetHost = '127.0.0.1';
$targetPort = 9999;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if ($socket === false) {
    die("Could not create socket\n");
}

socket_sendto($socket, $bytes, strlen($bytes), 0, $targetHost, $targetPort);
socket_close($socket);
?>
--EXPECT--
