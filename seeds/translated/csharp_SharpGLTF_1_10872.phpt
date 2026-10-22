--TEST--
Translated seed (inferredbugs-csharp: SharpGLTF/1)
--FILE--
<?php
class Node {
    public int $logicalIndex;
    public function __construct(int $i) { $this->logicalIndex = $i; }
}

class Accessor {
    public array $m;
    public function __construct(array $m) { $this->m = $m; }
    public function castToMatrix4x4Array(): array { return $this->m; }
}

class Skin {
    public ?Node $skeleton = null;
    public array $joints = [];
    public ?Accessor $inverseBindMatrices = null;

    public function getJoint(int $idx): array {
        return [$this->joints[$idx][0], $this->inverseBindMatrices->castToMatrix4x4Array()[$idx]];
    }

    public function isMatch(?Node $skeleton, array $joints): bool {
        if ($skeleton !== $this->skeleton) return false;
        if (count($joints) !== count($this->joints)) return false;
        for ($i = 0; $i < count($this->joints); $i++) {
            $dst = $this->getJoint($i);
            if ($joints[$i][0] !== $dst[0]) return false;
            if ($joints[$i][1] !== $dst[1]) return false;
        }
        return true;
    }
}

$n0 = new Node(0);
$n1 = new Node(1);

$s = new Skin();
$s->skeleton = $n0;
$s->joints = [[$n0, 0], [$n1, 1]];
$s->inverseBindMatrices = new Accessor([0, 1]);

$s->isMatch($n0, [[$n0, 0], [$n1, 1]]);
$s->isMatch($n1, [[$n0, 0], [$n1, 1]]);
$s->isMatch($n0, [[$n0, 0]]);
$s->getJoint(0);
?>
--EXPECT--
