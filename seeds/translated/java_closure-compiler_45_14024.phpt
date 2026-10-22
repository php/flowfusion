--TEST--
Translated seed (inferredbugs-java: closure-compiler/45)
--FILE--
<?php

class RemoveUnusedClassProperties {
    private $used = [];
    private $candidates = [];

    public function process($properties) {
        foreach ($properties as $key => $value) {
            if ($this->isPinningPropertyUse($key)) {
                $this->used[] = $key;
            } else {
                $this->candidates[] = $key;
            }
        }
        $this->removeUnused($properties);
    }

    private function removeUnused(&$properties) {
        foreach ($this->candidates as $candidate) {
            if (!in_array($candidate, $this->used)) {
                unset($properties[$candidate]);
            }
        }
    }

    private function isPinningPropertyUse($key) {
        return !($key[0] === '$'); // Simulating simple assignment check; e.g., "$x = 1" doesn't pin property.
    }
}

$properties = [
    'prop1' => 1,
    'usedProp' => 2,
    '$testProp' => 3
];

$remover = new RemoveUnusedClassProperties();
$remover->process($properties);
--EXPECT--
