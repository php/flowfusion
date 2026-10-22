--TEST--
Translated seed (inferredbugs-csharp: netmq/10)
--FILE--
<?php
$messages = ["I like NetMQ", "NetMQ Is quite awesome", "Agreed sockets on steroids with isotopes"];
foreach ($messages as $m) {
    $q = new SplQueue();
    $q->enqueue("ECHO");
    $q->enqueue($m);
    $cmd = $q->dequeue();
    $msg = $q->dequeue();
    $result = $cmd === "ECHO" ? "ECHO BACK : $msg" : "Unexpected command";
    echo $result, PHP_EOL;
}
$bad = "BadCommand1";
$q = new SplQueue();
$q->enqueue($bad);
$q->enqueue("whatever");
$cmd = $q->dequeue();
$msg = $q->dequeue();
$result = $cmd === "ECHO" ? "ECHO BACK : $msg" : "Unexpected command";
echo $result, PHP_EOL;
--EXPECT--
