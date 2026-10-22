--TEST--
Translated seed (inferredbugs-java: Discovery/15)
--FILE--
<?php

class DefaultStrategyTypeComparator {
    public function compare($left, $right) {
        if ($left === null) {
            return 0;
        }

        try {
            $leftValue = new \GMP($left);
            $rightValue = new \GMP($right);

            return $this->gmpCompare($leftValue, $rightValue);
        } catch (Exception $e) {
            // Handle exception silently
        }

        return $this->gmpCompare($left, $right);
    }

    private function gmpCompare($a, $b) {
        if (gmp_cmp($a, $b) < 0) {
            return -1;
        } elseif (gmp_cmp($a, $b) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}

$comparator = new DefaultStrategyTypeComparator();
$comparator->compare('12345678901234567890', '12345678901234567891');
$comparator->compare(null, '100');
$comparator->compare('500', '500');
$comparator->compare('99999999999999999999', '1');
--EXPECT--
