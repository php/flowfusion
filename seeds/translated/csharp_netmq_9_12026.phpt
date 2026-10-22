--TEST--
Translated seed (inferredbugs-csharp: netmq/9)
--FILE--
<?php
$messages = ["I like NetMQ", "NetMQ Is quite awesome", "Agreed sockets on steroids with isotopes"];
foreach ($messages as $actorMessage) {
    $queue = [];
    $queue[] = "ECHO";
    $queue[] = $actorMessage;
    $cmd = array_shift($queue);
    $msg = array_shift($queue);
    $result = ($cmd === "ECHO") ? "ECHO BACK : $msg" : "Unexpected command";
    echo $result, PHP_EOL;
}

$command = "BadCommand1";
$actorMessage = "whatever";
$queue = [];
$queue[] = $command;
$queue[] = $actorMessage;
$cmd = array_shift($queue);
$msg = array_shift($queue);
$result = ($cmd === "ECHO") ? "ECHO BACK : $msg" : "Unexpected command";
echo $result, PHP_EOL;
?>
--EXPECT--
