--TEST--
Translated seed (inferredbugs-csharp: netmq/2)
--FILE--
<?php
$addr = "tcp://127.0.0.1:8000";
$pid = pcntl_fork();
if ($pid === 0) {
    $ctx = new ZMQContext();
    $sub = new ZMQSocket($ctx, ZMQ::SOCKET_SUB);
    $sub->connect($addr);
    $sub->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "hello");
    usleep(500000);
    echo $sub->recv(), "\n";
    echo $sub->recv(), "\n";
    exit(0);
}
$ctx = new ZMQContext();
$pub = new ZMQSocket($ctx, ZMQ::SOCKET_PUB);
$pub->bind($addr);
usleep(1000000);
$pub->send("hello", ZMQ::MODE_SNDMORE);
$pub->send("message");
pcntl_wait($status);
--EXPECT--
