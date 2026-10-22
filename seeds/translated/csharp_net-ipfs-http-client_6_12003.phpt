--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/6)
--FILE--
<?php
$payload = '{"Peers":["/ip4/104.131.131.82/tcp/4001/ipfs/QmaCpDMGvV2BGHeYERUEnRQAwe3N8SzbUtfsmvsqQLuvuJ","/ip4/127.0.0.1/tcp/4001/ipfs/QmThrNbvLj7afQZhxH72m5Nn1qiVn3eMKWFYV49Zp2mv9D"]}';
$peers = json_decode($payload, true)['Peers'];
$count = count($peers);
$contains = in_array("/ip4/127.0.0.1/tcp/4001/ipfs/QmThrNbvLj7afQZhxH72m5Nn1qiVn3eMKWFYV49Zp2mv9D", $peers, true);
$copy = array_fill(0, $count + 1, null);
array_splice($copy, 1, $count, $peers);
foreach ($peers as $peer) {
    $peer;
}
echo $count . "\n";
echo ($contains ? "1" : "0") . "\n";
echo implode(",", $copy) . "\n";
?>
--EXPECT--
