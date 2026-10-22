--TEST--
Translated seed (inferredbugs-java: curator/1)
--FILE--
<?php
$server = 'localhost:2181';
$namespace = 'TestNamespace';

$client = new Zookeeper($server);
$client->setNamespace($namespace);
$client->create('/test', '', [], Zookeeper::EPHEMERAL);

$children = [];
for ($i = 0; $i < 10; $i++) {
    $client->create('/test/child', '', [], Zookeeper::EPHEMERAL_SEQUENTIAL);
}
$children = $client->getChildren('/test');
echo count($children) === 10 ? "Success" : "Failure";

$client->close();
?>
--EXPECT--
