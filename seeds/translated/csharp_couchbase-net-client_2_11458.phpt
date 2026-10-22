--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/2)
--FILE--
<?php
$bucketName = "bucket";
$configs = [];
$listeners = [];

$listener = (object)["Name" => $bucketName, "Notified" => false];
$listeners[$listener->Name] = $listener;

$oldConfigInfo = (object)["BucketConfig" => null];
$configs[$bucketName] = $oldConfigInfo;

$bucketConfig = (object)["Name" => $bucketName, "Value" => ["SurrogateHost" => "127.0.0.1"]];
$configInfo = (object)["BucketConfig" => $bucketConfig];

if (array_key_exists($bucketConfig->Name, $listeners) && array_key_exists($bucketConfig->Name, $configs)) {
    if ($configs[$bucketConfig->Name] === $oldConfigInfo) {
        $configs[$bucketConfig->Name] = $configInfo;
        $listeners[$bucketConfig->Name]->Notified = true;
    }
}

if (array_key_exists($listener->Name, $listeners)) {
    unset($listeners[$listener->Name]);
    if (array_key_exists($listener->Name, $configs)) {
        unset($configs[$listener->Name]);
    }
}

echo "ok\n";
?>
--EXPECT--
