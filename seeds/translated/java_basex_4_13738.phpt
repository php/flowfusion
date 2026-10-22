--TEST--
Translated seed (inferredbugs-java: basex/4)
--FILE--
<?php
class RangeToken {
    public $type;
    public $min;
    public $max;

    public function __construct($type, $min, $max) {
        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
    }
}

class NodeSet {
    public $ids;

    public function __construct($id) {
        $this->ids = array($id);
    }
}

class XPContext {
    public $local;

    public function __construct() {
        $this->local = new NodeSet(0);
    }
}

class RangeAccess {
    private $index;

    public function __construct($ind) {
        $this->index = $ind;
    }

    public function eval($ctx) {
        $ctx->local = new NodeSet($ctx->local->ids[0] + $this->index->min);
        return $ctx->local;
    }
}

$context = new XPContext();
$rangeToken = new RangeToken("exampleType", 2, 10);
$rangeAccess = new RangeAccess($rangeToken);
$rangeAccess->eval($context);
?>
--EXPECT--
