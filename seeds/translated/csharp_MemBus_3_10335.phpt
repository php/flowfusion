--TEST--
Translated seed (inferredbugs-csharp: MemBus/3)
--FILE--
<?php
class MessageA {}
class MessageB {}

class MockSubscription {
    public int $received = 0;
    public function push($m): void { $this->received++; }
}

class CompositeSubscription implements IteratorAggregate {
    private array $subs = [];
    private ?string $type = null;

    public function __construct(array $subs = []) {
        foreach ($subs as $s) $this->add($s);
    }

    public function add($s): void {
        $t = get_class($s);
        if ($this->type === null) $this->type = $t;
        elseif ($this->type !== $t) throw new InvalidArgumentException();
        $this->subs[] = $s;
    }

    public function push($m): void {
        foreach ($this->subs as $s) $s->push($m);
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->subs);
    }
}

$c = new CompositeSubscription();
try { $c->add(new MockSubscription()); $c->add(new stdClass()); } catch (Throwable $e) {}

$c = new CompositeSubscription([new MockSubscription(), new MockSubscription()]);
$count = 0;
foreach ($c as $s) if ($s instanceof MockSubscription) $count++;
$c->push(new MessageA());
$ok = ($count === 2);
foreach ($c as $s) if ($s instanceof MockSubscription) $ok = $ok && ($s->received === 1);
?>
--EXPECT--
