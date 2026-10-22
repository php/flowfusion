--TEST--
Translated seed (inferredbugs-java: asterisk-java/44)
--FILE--
<?php

for ($i = 10; $i-- > 0;) {
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_connect($sock, '127.0.0.1', 8080);
    $start = microtime(true);
    $ctr = 0;

    $buffer = '';
    while (true) {
        $bytes = socket_recv($sock, $buffer, 2048, 0);
        if ($bytes === false || $bytes === 0) {
            break;
        }
        $lines = explode("\n", $buffer);
        $ctr += count($lines);
    }

    socket_close($sock);
    echo "Fast $i:\t" . $ctr . "\t" . ((microtime(true) - $start) * 1000) . " ms\n";
}
--EXPECT--
