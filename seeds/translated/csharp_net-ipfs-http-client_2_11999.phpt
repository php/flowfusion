--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/2)
--FILE--
<?php
$result = '{"Peers":["/ip4/127.0.0.1/tcp/4001/p2p/QmTestPeer1","/ip4/192.168.0.1/tcp/4001/p2p/QmTestPeer2"]}';
$data = json_decode($result, true);
foreach (($data['Peers'] ?? []) as $peer) {
    $parts = explode('/', $peer);
    $last = end($parts);
    if ($last === false) {
        continue;
    }
}
?>
--EXPECT--
