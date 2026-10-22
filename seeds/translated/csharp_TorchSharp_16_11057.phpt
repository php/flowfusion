--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/16)
--FILE--
<?php
final class TensorWrap {
    public array $shape;
    public array $data;
    public function __construct(array $shape, array $data) {
        $this->shape = $shape;
        $this->data = $data;
    }
    public static function create(array $sizes): self {
        $shape = $sizes;
        if (!$sizes) $shape = [0];
        $len = 1;
        foreach ($sizes as $s) $len *= $s;
        return new self($shape, array_fill(0, $len, 0));
    }
    public function cloneTensor(): self {
        return new self($this->shape, $this->data);
    }
    public function cloneEmpty(array $dimensions): self {
        if ($dimensions === []) throw new InvalidArgumentException("Dimensions must contain elements.");
        if (count($dimensions) > 4) throw new InvalidArgumentException("Cannot reshape tensor with more than 4 dimensions");
        $len = 1;
        foreach ($dimensions as $d) $len *= $d;
        if ($len !== count($this->data)) throw new InvalidArgumentException("Cannot reshape array due to mismatch in lengths.");
        return new self($dimensions, array_fill(0, $len, 0));
    }
    public function reshape(array $dimensions): self {
        if ($dimensions === []) throw new InvalidArgumentException("Dimensions must contain elements.");
        $len = 1;
        foreach ($dimensions as $d) $len *= $d;
        if ($len !== count($this->data)) throw new InvalidArgumentException("Cannot reshape array due to mismatch in lengths.");
        if (count($dimensions) > 2) throw new InvalidArgumentException("Cannot reshape tensor with more than 4 dimensions");
        return new self($dimensions, $this->data);
    }
}

$t = TensorWrap::create([]);
$t2 = $t->cloneTensor();
$t3 = $t2->cloneEmpty([0]);
$t4 = $t3->reshape([0]);
$t5 = TensorWrap::create([2, 3]);
$t6 = $t5->cloneTensor();
$t7 = $t6->cloneEmpty([2, 3]);
$t8 = $t7->reshape([6]);
echo count($t->data) . "\n";
echo count($t5->data) . "\n";
echo count($t8->data) . "\n";
?>
--EXPECT--
