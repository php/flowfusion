--TEST--
Translated seed (inferredbugs-csharp: netmq/3)
--FILE--
<?php
$s = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
if ($s === false) {
    exit(1);
}
stream_set_blocking($s[0], true);
stream_set_blocking($s[1], true);

fwrite($s[0], "1");
$m = fread($s[1], 1);

echo $m;
?>
--EXPECT--
