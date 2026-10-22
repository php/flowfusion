--TEST--
Translated seed (inferredbugs-java: curator/3)
--FILE--
<?php

$server = 'localhost:2181'; // Replace with your Zookeeper server address
$namespace = 'TestNamespace';

$zk = new Zookeeper($server);
$zk->create('/test', '', [], Zookeeper::EPHEMERAL);
$zk->create('/test/child', 'hey', [], 0);
$child = $zk->getChildren('/test');
$zk->delete('/test/child');
$zk->delete('/test');
--EXPECT--
