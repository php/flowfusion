--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/12)
--FILE--
<?php
function totalLength(array $sizes): int {
    $n = 1;
    foreach ($sizes as $s) $n *= $s;
    return $n;
}

class Tensor {
    public array $shape;
    public array $data;
    function __construct(array $shape, array $data = []) {
        $this->shape = $shape;
        $this->data = $data ?: array_fill(0, totalLength($shape), 0);
    }
    function cloneTensor(): self {
        return new self($this->shape, $this->data);
    }
    function cloneEmpty(array $dimensions): self {
        return new self($dimensions, array_fill(0, totalLength($dimensions), 0));
    }
    function reshape(array $dimensions): self {
        if (!$dimensions) throw new InvalidArgumentException("Dimensions must contain elements.");
        if (totalLength($dimensions) !== count($this->data)) throw new InvalidArgumentException("Cannot reshape array due to mismatch in lengths.");
        return new self($dimensions, $this->data);
    }
}

$t = new Tensor([2, 3]);
$t->data[4] = 7;
$c = $t->cloneTensor();
$e = $t->cloneEmpty([2, 3]);
$r = $t->reshape([3, 2]);
$x = new Tensor([], []);
$y = $x->cloneEmpty([0]);
echo count($c->data) + count($e->data) + count($r->data) + count($y->data), PHP_EOL;
?>
--EXPECT--
