--TEST--
Translated seed (inferredbugs-java: blockchain-java/4)
--FILE--
<?php

class Block {}

class SerializeUtils {
    public static function serialize($data) {
        return serialize($data);
    }

    public static function deserialize($data) {
        return unserialize($data);
    }
}

$DB_FILE = "blockchain.db";
$BLOCKS_BUCKET_PREFIX = "blocks_";
$rocksDB = [];
$lastBlockHash = "";

function initRocksDB() {
    global $rocksDB, $DB_FILE;
    if (file_exists($DB_FILE)) {
        $rocksDB = unserialize(file_get_contents($DB_FILE));
    } else {
        $rocksDB = [];
    }
}

function saveDB() {
    global $rocksDB, $DB_FILE;
    file_put_contents($DB_FILE, serialize($rocksDB));
}

function putLastBlockHash($tipBlockHash) {
    global $rocksDB, $BLOCKS_BUCKET_PREFIX;
    $rocksDB[$BLOCKS_BUCKET_PREFIX . "l"] = $tipBlockHash;
}

function getLastBlockHash() {
    global $rocksDB, $BLOCKS_BUCKET_PREFIX;
    return isset($rocksDB[$BLOCKS_BUCKET_PREFIX . "l"]) ? $rocksDB[$BLOCKS_BUCKET_PREFIX . "l"] : "";
}

function putBlock($block) {
    global $rocksDB, $BLOCKS_BUCKET_PREFIX;
    $rocksDB[$BLOCKS_BUCKET_PREFIX . serialize($block)] = SerializeUtils::serialize($block);
}

function getBlock($blockHash) {
    global $rocksDB, $BLOCKS_BUCKET_PREFIX;
    return isset($rocksDB[$BLOCKS_BUCKET_PREFIX . $blockHash]) ? SerializeUtils::deserialize($rocksDB[$BLOCKS_BUCKET_PREFIX . $blockHash]) : null;
}

initRocksDB();
putLastBlockHash("example_hash");
$lastHash = getLastBlockHash();
putBlock(new Block());
$retrievedBlock = getBlock(serialize(new Block()));
saveDB();
--EXPECT--
