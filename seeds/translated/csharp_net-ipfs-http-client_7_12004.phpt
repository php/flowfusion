--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/7)
--FILE--
<?php
$json = '{"ID":"QmPeer","PublicKey":"key","Addresses":["/ip4/127.0.0.1/tcp/4001","/ip6/::1/tcp/4001"],"AgentVersion":"go-ipfs/0.4.0","ProtocolVersion":"ipfs/0.1.0","Extra":{"nested":[1,2,3],"empty":null}}';
for ($i = 0; $i < 1000; $i++) {
    $peer = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    $json = json_encode($peer, JSON_THROW_ON_ERROR);
}
echo $json;
?>
--EXPECT--
