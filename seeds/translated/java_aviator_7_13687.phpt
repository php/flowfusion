--TEST--
Translated seed (inferredbugs-java: aviator/7)
--FILE--
<?php
class ASMCodeGenerator {
    private $operandsCount = 0;
    private $maxStacks = 0;
    private $maxLocals = 2;

    private function setMaxStacks($newMaxStacks) {
        if ($newMaxStacks > $this->maxStacks) {
            $this->maxStacks = $newMaxStacks;
        }
    }

    private function popOperand() {
        $this->operandsCount--;
    }

    private function popOperandMultiple($n) {
        $this->operandsCount -= $n;
    }

    private function pushOperand($extras = 0) {
        $this->operandsCount++;
        $this->setMaxStacks($this->operandsCount + $extras);
    }

    private function visitBinOperator($op, $method) {
        $this->popOperandMultiple(2);
        $this->pushOperand();
    }

    private function doCompareAndJump($condition, $op) {
        // Simulate comparison logic
        $result = true; // Placeholder for comparison result
        if ($condition) {
            $this->pushOperand();
        } else {
            $this->popOperand();
        }
    }

    public function onAdd() {
        $this->visitBinOperator('ADD', 'add');
    }

    public function onSub() {
        $this->visitBinOperator('SUB', 'sub');
    }

    public function onMul() {
        $this->visitBinOperator('MUL', 'mul');
    }

    public function onDiv() {
        $this->visitBinOperator('DIV', 'div');
    }

    public function onEq() {
        $this->doCompareAndJump(true, 'EQ');
    }

    public function onNeq() {
        $this->doCompareAndJump(false, 'NEQ');
    }

    public function execute() {
        $this->onAdd();
        $this->onSub();
        $this->onMul();
        $this->onDiv();
        $this->onEq();
        $this->onNeq();
    }
}

$generator = new ASMCodeGenerator();
$generator->execute();
?>
--EXPECT--
