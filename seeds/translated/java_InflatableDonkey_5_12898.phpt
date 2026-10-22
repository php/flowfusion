--TEST--
Translated seed (inferredbugs-java: InflatableDonkey/5)
--FILE--
<?php
class AssetDownloader {
    private $chunkClient;
    private $store;

    public function __construct($chunkClient, $store) {
        $this->chunkClient = $chunkClient;
        $this->store = $store;
    }

    public function accept($httpClient, $voodoos, $consumer) {
        $keks = $this->keyEncryptionKeys($voodoos);
        foreach ($voodoos as $v) {
            $this->fetch($httpClient, $keks, $v['containers'], $v['asset'], $consumer);
        }
    }

    private function fetch($httpClient, $keks, $containers, $asset, $consumer) {
        $map = [];
        foreach ($containers as $index => $container) {
            $chunks = $this->applyKek($keks, $container, $httpClient, $index);
            if ($chunks === null) return;
            $map = array_merge($map, $chunks);
        }
        $this->assemble($map, $asset['chunkReferences'], $consumer, $asset);
    }

    private function applyKek($keks, $container, $httpClient, $index) {
        // Simulated fetch returning random chunks or null for failure
        return rand(0, 1) ? [$index => "ChunkData"] : null;
    }

    private function assemble($map, $references, $consumer, $asset) {
        if (array_diff($references, array_keys($map))) {
            echo "-- assemble() - missing chunks\n";
            return;
        }
        $chunkList = array_intersect_key($map, array_flip($references));
        $consumer($asset, $chunkList);
    }

    private function keyEncryptionKeys($voodoos) {
        // Simulated key extraction
        return function($container) { return "KeyData"; };
    }
}

$downloader = new AssetDownloader("ChunkClient", "Store");
$downloader->accept("HttpClient", 
    [
        ['containers' => [1 => 'container1'], 'asset' => ['chunkReferences' => [1]]],
        ['containers' => [2 => 'container2'], 'asset' => ['chunkReferences' => [2]]]
    ], 
    function($asset, $chunks) {
        echo "Downloaded " . json_encode($asset) . " with chunks: " . json_encode($chunks) . "\n";
    });
?>
--EXPECT--
