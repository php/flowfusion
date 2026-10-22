--TEST--
Translated seed (inferredbugs-csharp: HarmonyX/1)
--FILE--
<?php
class PatchInfo {
    public $prefixes;
    public $postfixes;
}

class Patch {
    public $index;
    public $owner;
    public $patch;
    public $priority;
    public $before;
    public $after;

    public function __construct($index, $owner, $patch, $priority, $before, $after) {
        $this->index = $index;
        $this->owner = $owner;
        $this->patch = $patch;
        $this->priority = $priority;
        $this->before = $before;
        $this->after = $after;
    }

    public function compareTo($obj) {
        $theirOwner = $obj->owner;
        $theirPriority = $obj->priority;
        $theirIndex = $obj->index;

        if ($this->before !== null && array_search($theirOwner, $this->before, true) !== false) return -1;
        if ($this->after !== null && array_search($theirOwner, $this->after, true) !== false) return 1;
        if ($this->priority !== $theirPriority) return -($this->priority <=> $theirPriority);
        return $this->index <=> $theirIndex;
    }

    public function equals($obj) {
        return $obj !== null && $obj instanceof Patch && $this->patch === $obj->patch;
    }

    public function getHashCode() {
        return spl_object_id($this->patch);
    }
}

$pi = new PatchInfo();
$p1 = new Patch(1, "a", new stdClass(), 10, ["b"], ["c"]);
$p2 = new Patch(2, "b", $p1->patch, 5, null, null);
$pi->prefixes = [$p1, $p2];
$pi->postfixes = [$p2, $p1];

$s = serialize($pi);
$u = unserialize($s);

$a = $u->prefixes[0];
$b = $u->prefixes[1];
$a->compareTo($b);
$a->equals($b);
$a->getHashCode();
?>
--EXPECT--
