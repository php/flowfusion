--TEST--
Translated seed (inferredbugs-java: curator/2)
--FILE--
<?php
$connectString = "localhost:2181"; // Replace with your Zookeeper connection string
$namespace = "TestNamespace";

$client = new Zookeeper($connectString);
$client->setNamespace($namespace);
$client->create("/test", "", ["zookeeper:create", "zookeeper:delete"]);

$data = $client->get("/test");
if ($data === false) {
    $client->create("/test", "foo");
}

$children = $client->getChildren("/test");
if (count($children) === 0) {
    for ($i = 0; $i < 10; $i++) {
        $client->create("/test/child" . $i, "");
    }
}

$children = $client->getChildren("/test");
if (count($children) === 10) {
    $client->delete("/test");
}

$client->close();
--EXPECT--
