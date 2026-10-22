--TEST--
Translated seed (inferredbugs-csharp: FastExpressionCompiler/8)
--FILE--
<?php

class ExpressionVisitor
{
    public function visit($node)
    {
        return $node ? $this->accept($node) : null;
    }

    public function visitArray($nodes)
    {
        $newNodes = null;
        $count = count($nodes);
        for ($i = 0; $i < $count; ++$i) {
            $newNode = $this->visit($nodes[$i]);
            if ($newNodes !== null) {
                $newNodes[$i] = $newNode;
            } elseif ($newNode !== $nodes[$i]) {
                $newNodes = array_fill(0, $count, null);
                for ($j = 0; $j < $i; ++$j) {
                    $newNodes[$j] = $nodes[$j];
                }
                $newNodes[$i] = $newNode;
            }
        }
        return $newNodes ?? $nodes;
    }

    public function visitAndConvert($nodes, $visit)
    {
        $newNodes = null;
        $count = count($nodes);
        for ($i = 0; $i < $count; ++$i) {
            $newNode = $visit($nodes[$i]);
            if ($newNodes !== null) {
                $newNodes[$i] = $newNode;
            } elseif ($newNode !== $nodes[$i]) {
                $newNodes = array_fill(0, $count, null);
                for ($j = 0; $j < $i; ++$j) {
                    $newNodes[$j] = $nodes[$j];
                }
                $newNodes[$i] = $newNode;
            }
        }
        return $newNodes ?? $nodes;
    }

    protected function accept($node)
    {
        // Simulate accepting a node based on type, for simplicity using an array
        if (is_array($node)) {
            return $this->visitArray($node);
        }
        return $node;
    }
}

function main()
{
    $visitor = new ExpressionVisitor();
    $nodes = [1, 2, 3, 4];
    $result = $visitor->visitArray($nodes);
    var_dump($result);
}

main();
--EXPECT--
