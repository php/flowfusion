--TEST--
Translated seed (inferredbugs-java: blockchain-java/5)
--FILE--
<?php

class Block {
    public $hash;
    public function __construct($hash) {
        $this->hash = $hash;
    }
}

class RocksDBUtils {
    private static $instance;
    private $data = [];
    private const DB_FILE = 'blockchain.db';
    private const BLOCKS_BUCKET_PREFIX = 'blocks_';

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new RocksDBUtils();
        }
        return self::$instance;
    }

    public function putLastBlockHash($tipBlockHash) {
        $this->data[self::BLOCKS_BUCKET_PREFIX . 'l'] = $tipBlockHash;
    }

    public function getLastBlockHash() {
        return $this->data[self::BLOCKS_BUCKET_PREFIX . 'l'] ?? '';
    }

    public function putBlock($block) {
        $this->data[self::BLOCKS_BUCKET_PREFIX . $block->hash] = $block;
    }

    public function getBlock($blockHash) {
        return $this->data[self::BLOCKS_BUCKET_PREFIX . $blockHash] ?? null;
    }
}

$rocksDB = RocksDBUtils::getInstance();
$rocksDB->putLastBlockHash('block_hash_1');
$lastHash = $rocksDB->getLastBlockHash();
$block = new Block($lastHash);
$rocksDB->putBlock($block);
$retrievedBlock = $rocksDB->getBlock($lastHash);
--EXPECT--
