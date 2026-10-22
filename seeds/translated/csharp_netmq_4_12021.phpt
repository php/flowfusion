--TEST--
Translated seed (inferredbugs-csharp: netmq/4)
--FILE--
<?php
$ctx = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
if ($ctx === false) exit(0);
[$s1, $s2] = $ctx;
stream_set_blocking($s1, true);
stream_set_blocking($s2, true);
fwrite($s1, "1");
echo fread($s2, 1), PHP_EOL;
?>
--EXPECT--
