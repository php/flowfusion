--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/20)
--FILE--
<?php
class Observer {
    public string $name;
    public bool $disposed = false;
    function __construct(string $name) { $this->name = $name; }
    function notifyConfigChanged($config) {}
    function dispose() { $this->disposed = true; }
}

$observers = [];
$configs = [];

$obs = new Observer("bucket");
$observers[$obs->name] = $obs;
$configs[$obs->name] = (object)["rev" => 1, "name" => $obs->name];

$bucketConfig = (object)["name" => "bucket", "rev" => 2];
if (isset($observers[$bucketConfig->name])) {
    if (isset($configs[$bucketConfig->name])) {
        $old = $configs[$bucketConfig->name];
        if ($bucketConfig->rev > $old->rev) {
            $newConfig = (object)["bucketConfig" => $bucketConfig];
            if (($configs[$bucketConfig->name] = $newConfig) === $newConfig) {
                $observers[$bucketConfig->name]->notifyConfigChanged($newConfig);
            }
        }
    }
}

foreach ($observers as $name => $observer) {
    unset($observers[$name]);
    unset($configs[$name]);
    $observer->dispose();
}

$observers = [];
$configs = [];
?>
--EXPECT--
