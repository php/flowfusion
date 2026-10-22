--TEST--
Translated seed (inferredbugs-csharp: FreeRedis/3)
--FILE--
<?php
class Slab {
    public string $buf;
    public bool $active = true;
    function __construct(int $len) { $this->buf = str_repeat("\0", $len); }
    function dispose(): void { $this->active = false; $this->buf = ""; }
}

class Block {
    public ?Pool $pool;
    public ?Slab $slab;
    public int $offset;
    public int $length;
    function __construct(?Pool $pool, ?Slab $slab, int $offset, int $length) {
        $this->pool = $pool; $this->slab = $slab; $this->offset = $offset; $this->length = $length;
    }
}

class Pool {
    private const BLOCK_SIZE = 4096;
    private const BLOCK_COUNT = 32;
    private array $blocks = [];
    private array $slabs = [];
    private bool $disposed = false;

    function rent(int $size = -1): Block {
        if ($this->disposed) return new Block(null, null, 0, 0);
        if ($this->blocks) {
            $b = array_pop($this->blocks);
            return $b;
        }
        return $this->allocateSlab();
    }

    private function allocateSlab(): Block {
        $slab = new Slab(self::BLOCK_SIZE * self::BLOCK_COUNT);
        $this->slabs[] = $slab;
        $last = null;
        for ($i = 0; $i < self::BLOCK_COUNT; $i++) {
            $last = new Block($this, $slab, $i * self::BLOCK_SIZE, self::BLOCK_SIZE);
            if ($i !== self::BLOCK_COUNT - 1) $this->returnBlock($last);
        }
        return $last;
    }

    function returnBlock(Block $block): void {
        if (!$this->disposed) $this->blocks[] = $block;
    }

    function dispose(): void {
        if ($this->disposed) return;
        $this->disposed = true;
        foreach ($this->slabs as $slab) $slab->dispose();
        $this->blocks = [];
    }
}

$pool = new Pool();
$a = $pool->rent();
$b = $pool->rent();
$pool->returnBlock($a);
$c = $pool->rent();
$pool->dispose();
$d = $pool->rent();
echo "ok\n";
?>
--EXPECT--
