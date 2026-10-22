--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/43)
--FILE--
<?php
class ClusterController {
    public $disposed = false;
    public $observed = [];
    function CreateBucket($name, $password = null) { $this->observed[$name] = true; return (object)['name' => $name, 'password' => $password]; }
    function DestroyBucket($bucket) { unset($this->observed[$bucket->name]); }
    function IsObserving($name) { return isset($this->observed[$name]); }
    function Info() { return (object)['status' => 'ok']; }
    function Dispose() { $this->disposed = true; }
}

class Cluster {
    private $configuration;
    private $clusterController;
    private $disposed = false;

    function __construct($configuration = null, $clusterController = null) {
        $this->configuration = $configuration ?? new stdClass();
        $this->clusterController = $clusterController ?? new ClusterController();
        json_encode($this->configuration);
    }

    function OpenBucket($bucketname = 'default', $password = null) {
        if (func_num_args() === 1) {
            if ($bucketname === null) throw new InvalidArgumentException('bucketname');
            if (trim($bucketname) === '') throw new InvalidArgumentException('bucketname cannot be null, empty or whitespace.');
        }
        return $password === null ? $this->clusterController->CreateBucket($bucketname) : $this->clusterController->CreateBucket($bucketname, $password);
    }

    function CloseBucket($bucket) {
        if ($bucket === null) throw new InvalidArgumentException('bucket');
        $this->clusterController->DestroyBucket($bucket);
    }

    function IsOpen($bucketName) { return $this->clusterController->IsObserving($bucketName); }
    function Dispose() { if (!$this->disposed) { $this->clusterController->Dispose(); $this->disposed = true; } }
}

$c = new Cluster((object)['a' => 1, 'b' => [1, 2, 3]]);
$b = $c->OpenBucket();
$c->CloseBucket($b);
$c->OpenBucket("name", "pass");
$c->IsOpen("name");
$c->Dispose();
?>
--EXPECT--
