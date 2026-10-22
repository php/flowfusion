--TEST--
Translated seed (inferredbugs-csharp: design-patterns-explained-with-food/1)
--FILE--
<?php
class CafeChairIcon {
    public function hasSeatCushions(): bool {
        return false;
    }

    public function deepClone() {
        $cafeChairIcon = null;
        return $cafeChairIcon->deepClone();
    }
}

$icon = new CafeChairIcon();
$icon->deepClone();
?>
--EXPECT--
