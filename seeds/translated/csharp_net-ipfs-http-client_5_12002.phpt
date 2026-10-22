--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/5)
--FILE--
<?php
$payload = '{"Peers":["/ip4/104.131.131.82/tcp/4001/ipfs/QmaCpDMGvV2BGHeYERUEnRQAwe3N8SzbUtfsmvsqQLuvuJ","/ip4/127.0.0.1/tcp/4001/ipfs/QmThrNbvLj7afQZhxH72m5Nn1qiVn3eMKWFYV49Zp2mv9D"]}';
$data = json_decode($payload, true);
$peers = $data['Peers'];
$count = count($peers);
$contains = in_array("/ip4/127.0.0.1/tcp/4001/ipfs/QmThrNbvLj7afQZhxH72m5Nn1qiVn3eMKWFYV49Zp2mv9D", $peers, true);
$copy = array_fill(0, $count + 1, null);
array_splice($copy, 1, $count, $peers);
$iter = 0;
foreach ($peers as $peer) {
    $iter += strlen($peer) > 0 ? 1 : 0;
}
echo ($count . ":" . ($contains ? "1" : "0") . ":" . $iter . ":" . $copy[1] . PHP_EOL);
?>
--EXPECT--
