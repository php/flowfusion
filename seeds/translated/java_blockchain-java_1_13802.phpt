--TEST--
Translated seed (inferredbugs-java: blockchain-java/1)
--FILE--
<?php

class RocksDBUtils {
    private static $instance;
    private static $rocksDB;
    private const DB_FILE = 'blockchain.db';
    private const BLOCKS_BUCKET_PREFIX = 'blocks_';

    private function __construct() {
        $this->initRocksDB();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initRocksDB() {
        if (!file_exists(self::DB_FILE)) {
            file_put_contents(self::DB_FILE, '');
        }
    }

    public function putLastBlockHash($tipBlockHash) {
        file_put_contents(self::DB_FILE, self::BLOCKS_BUCKET_PREFIX . "l:" . $tipBlockHash . PHP_EOL, FILE_APPEND);
    }

    public function getLastBlockHash() {
        $lines = file(self::DB_FILE, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            if (strpos($line, self::BLOCKS_BUCKET_PREFIX . "l:") === 0) {
                return substr($line, strlen(self::BLOCKS_BUCKET_PREFIX . "l:"));
            }
        }
        return "";
    }
}

$rocksDBUtils = RocksDBUtils::getInstance();
$rocksDBUtils->putLastBlockHash('somehashvalue');
echo $rocksDBUtils->getLastBlockHash();
--EXPECT--
