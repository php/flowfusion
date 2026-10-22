--TEST--
Translated seed (inferredbugs-java: blockchain-java/2)
--FILE--
<?php

class Block {
    public $hash;
    public function __construct($hash) {
        $this->hash = $hash;
    }
}

class RocksDBUtils {
    private static $instance = null;
    private static $dbFile = "blockchain.db";
    private static $blocksBucketPrefix = "blocks_";
    private $data = [];

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new RocksDBUtils();
        }
        return self::$instance;
    }

    public function putLastBlockHash($tipBlockHash) {
        $this->data[self::$blocksBucketPrefix . "l"] = $tipBlockHash;
    }

    public function getLastBlockHash() {
        return $this->data[self::$blocksBucketPrefix . "l"] ?? "";
    }

    public function putBlock($block) {
        $this->data[self::$blocksBucketPrefix . $block->hash] = serialize($block);
    }

    public function getBlock($blockHash) {
        return unserialize($this->data[self::$blocksBucketPrefix . $blockHash] ?? "");
    }
}

$rocksDBUtils = RocksDBUtils::getInstance();
$rocksDBUtils->putLastBlockHash("0000000000000000000769c6f3710c583b8d4bdc687352be6e1b3eede0ae0a3c");
echo $rocksDBUtils->getLastBlockHash();
$block = new Block("00000000000000000004929c6f3710c583b8d4bdc687352be6e1b3eede0ae0a3d");
$rocksDBUtils->putBlock($block);
$retrievedBlock = $rocksDBUtils->getBlock("00000000000000000004929c6f3710c583b8d4bdc687352be6e1b3eede0ae0a3d");
echo $retrievedBlock->hash;
--EXPECT--
