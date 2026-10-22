--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/7)
--FILE--
<?php
declare(strict_types=1);

class T { public function __construct(public string $p) {} }
class E { public function __construct(public string $p, public bool $d) {} }
class S { public function __construct(public string $p) {} }

class X {
    public array $c = [];
    public function __construct(public T $s, public T $d, public bool $r = true) {}
    public function g(string $p): array { return [new T($this->s->p . '/' . $p), new T($this->d->p . '/' . $p)]; }
    public function a(string $p, callable $f): void { $this->c[] = [$p, $f]; }
    public function b(S $o): S { return $o; }
}

class Y {
    public function __construct(public X $b, public string $r) {
        $this->t = [new S('dir1'), new E('bad', true), new S('file1')];
        [$this->s, $this->d] = $this->b->g($this->r);
    }
    public function e(): void {
        foreach ($this->t as $i) {
            if ($i instanceof E) continue;
            if ($i->d ?? false) {
                $this->b->a($i->p, function () use ($i) { return $i->p; });
            } else {
                $o = $this->b->b($i);
                $this->b->a($i->p, function () use ($i, $o) { return $i->p . ':' . $o->p; });
            }
        }
    }
}

$x = new X(new T('/src'), new T('/dst'));
$y = new Y($x, 'sub');
$y->e();
echo count($x->c), "\n";
--EXPECT--
