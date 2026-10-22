--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/4)
--FILE--
<?php
$json = '{"Peers":[["/ip4/104.131.131.82/tcp/4001/ipfs/QmaCpDMGvV2BGHeYERUEnRQAwe3N8SzbUtfsmvsqQLuvuJ"],["/ip4/127.0.0.1/tcp/4001/ipfs/QmThrNbvLj7afQZhxH72m5Nn1qiVn3eMKWFYV49Zp2mv9D"]]}';
$data = json_decode($json, true);
$peers = $data['Peers'] ?? [];
$count = count($peers);
$flat = [];
for ($i = 0; $i < $count; $i++) {
    $peer = $peers[$i];
    if (is_array($peer)) {
        for ($j = 0, $n = count($peer); $j < $n; $j++) {
            $flat[] = (string)$peer[$j];
        }
    } else {
        $flat[] = (string)$peer;
    }
}
$copy = array_fill(0, $count + 1, null);
array_splice($copy, 1, 0, $flat);
$contains = in_array('/ip4/127.0.0.1/tcp/4001/ipfs/QmThrNbvLj7afQZhxH72m5Nn1qiVn3eMKWFYV49Zp2mv9D', $flat, true);
$removed = array_values(array_filter($flat, fn($v) => $v !== '/ip4/104.131.131.82/tcp/4001/ipfs/QmaCpDMGvV2BGHeYERUEnRQAwe3N8SzbUtfsmvsqQLuvuJ'));
echo ($contains ? "1" : "0") . ":" . count($copy) . ":" . count($removed) . PHP_EOL;
?>
--EXPECT--
