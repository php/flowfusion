--TEST--
Translated seed (inferredbugs-java: Oak/1)
--FILE--
<?php

class Block {
    private $data;

    public function __construct($size) {
        $this->data = str_repeat("\0", $size);
    }

    public function reset() {
        $this->data = str_repeat("\0", strlen($this->data));
    }

    public function clean() {
        $this->data = null;
    }
}

class BlocksPool {
    private static $instance = null;
    private $blocks = [];
    private static $BLOCK_SIZE = 256 * 1024 * 1024;
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

    public function blockSize() {
        return self::$BLOCK_SIZE;
    }

    public function getBlock() {
        $b = null;
        while ($b === null) {
            if (empty($this->blocks)) {
                $this->prealloc(self::NUMBER_OF_BLOCKS / 2);
            }
            $b = array_pop($this->blocks);
        }
        return $b;
    }

    public function returnBlock($b) {
        $b->reset();
        $this->blocks[] = $b;
        if (count($this->blocks) > self::EXCESS_POOL_RATIO * self::NUMBER_OF_BLOCKS) {
            for ($i = 0; $i < self::NUMBER_OF_BLOCKS; $i++) {
                $this->blocks[$i]->clean();
            }
            $this->blocks = array_slice($this->blocks, self::NUMBER_OF_BLOCKS);
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
