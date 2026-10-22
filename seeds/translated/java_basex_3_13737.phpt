--TEST--
Translated seed (inferredbugs-java: basex/3)
--FILE--
<?php
class IndexAccess {
    private $index;

    public function __construct($ind) {
        $this->index = $ind;
    }

    public function eval($ctx) {
        $ctx['local'] = [$ctx['local']['data']['ids'][$this->index][0], $ctx];
        return $ctx['local'];
    }

    public function plan($ser) {
        $ser->openElement($this, 'TYPE', (string)$this->index);
        $ser->item($this->index);
        $ser->closeElement($this);
    }

    public function color() {
        return "CC99FF";
    }

    public function __toString() {
        return "IndexAccess(" . $this->index . ", \"" . (string)$this->index . "\")";
    }
}

$indexAccess = new IndexAccess(0);
$ctx = ['local' => ['data' => ['ids' => [0]]]];
$indexAccess->eval($ctx);
$indexAccess->plan(new class {
    public function openElement($obj, $type, $indexType) {}
    public function item($index) {}
    public function closeElement($obj) {}
});
echo $indexAccess; 
?>
--EXPECT--
