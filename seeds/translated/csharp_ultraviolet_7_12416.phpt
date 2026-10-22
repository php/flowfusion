--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/7)
--FILE--
<?php
class Selector {
    public int $priority;
    private bool $match;
    public function __construct(int $priority, bool $match) {
        $this->priority = $priority;
        $this->match = $match;
    }
    public function matchesElement($element): bool {
        return $this->match;
    }
}

$selectors = [
    new Selector(1, true),
    new Selector(3, false),
    new Selector(3, true),
    new Selector(2, true),
    new Selector(3, true),
];

$element = new stdClass();
$selected = null;
$priority = 0;

foreach ($selectors as $potentialMatch) {
    if ($potentialMatch->matchesElement($element)) {
        if ($selected === null || $selected->priority >= $priority) {
            $selected = $potentialMatch;
            $priority = $selected->priority;
        }
    }
}

echo ($selected !== null ? $selected->priority : -1), PHP_EOL;
--EXPECT--
