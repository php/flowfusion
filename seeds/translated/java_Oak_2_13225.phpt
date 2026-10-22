--TEST--
Translated seed (inferredbugs-java: Oak/2)
--FILE--
<?php

class Block {
    private $size;
    private $data;

    public function __construct($size) {
        $this->size = $size;
        $this->data = str_repeat('0', $size); // Preallocate memory
    }

    public function reset() {
        $this->data = str_repeat('0', $this->size);
    }

    public function clean() {
        $this->data = null; // Free memory
    }
}

class BlocksPool {
    private static $instance = null;
    private $blocks = [];
    private static $BLOCK_SIZE = 256 * 1024 * 1024; // 256MB
    private const NUMBER_OF_BLOCKS = 10;
    private const EXCESS_POOL_RATIO = 3;

    private function __construct() {
        $this->prealloc(self::NUMBER_OF_BLOCKS);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new BlocksPool();
        }
        return self::$instance;
    }

    public static function setBlockSize($blockSize) {
        self::$BLOCK_SIZE = $blockSize;
        self::$instance = new BlocksPool();
    }

    public function getBlock() {
        if (empty($this->blocks)) {
            $this->prealloc(self::NUMBER_OF_BLOCKS / 2);
        }
        return array_pop($this->blocks);
    }

    public function returnBlock($b) {
        $b->reset();
        $this->blocks[] = $b;
        if (count($this->blocks) > self::EXCESS_POOL_RATIO * self::NUMBER_OF_BLOCKS) {
            for ($i = 0; $i < self::NUMBER_OF_BLOCKS; $i++) {
                if (!empty($this->blocks)) {
                    array_pop($this->blocks)->clean();
                }
            }
        }
    }

    private function prealloc($numOfBlocks) {
        for ($i = 0; $i < $numOfBlocks; $i++) {
            $this->blocks[] = new Block(self::$BLOCK_SIZE);
        }
    }
}

$pool = BlocksPool::getInstance();
$block = $pool->getBlock();
$pool->returnBlock($block);
--EXPECT--
