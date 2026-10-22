--TEST--
Translated seed (inferredbugs-java: blockchain-java/3)
--FILE--
<?php

$dbFile = "blockchain.db";
$blocksBucketPrefix = "blocks_";

$rocksDB = [];

function putLastBlockHash($tipBlockHash) {
    global $rocksDB, $blocksBucketPrefix;
    $rocksDB[$blocksBucketPrefix . "l"] = $tipBlockHash;
}

function getLastBlockHash() {
    global $rocksDB, $blocksBucketPrefix;
    return isset($rocksDB[$blocksBucketPrefix . "l"]) ? $rocksDB[$blocksBucketPrefix . "l"] : "";
}

function putBlock($block) {
    global $rocksDB, $blocksBucketPrefix;
    $rocksDB[$blocksBucketPrefix . $block['hash']] = serialize($block);
}

function getBlock($blockHash) {
    global $rocksDB, $blocksBucketPrefix;
    return isset($rocksDB[$blocksBucketPrefix . $blockHash]) ? unserialize($rocksDB[$blocksBucketPrefix . $blockHash]) : null;
}

putLastBlockHash("exampleHash");
$getLastHash = getLastBlockHash();
putBlock(['hash' => 'exampleBlockHash', 'data' => 'exampleData']);
$getBlock = getBlock('exampleBlockHash');

?>
--EXPECT--
