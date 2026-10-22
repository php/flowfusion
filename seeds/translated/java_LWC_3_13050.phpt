--TEST--
Translated seed (inferredbugs-java: LWC/3)
--FILE--
<?php

$instance = null;
$enabled = false;

class LWC {
    private static $instance;
    private $enabled = false;

    public function __construct() {
        if (self::$instance === null) {
            self::$instance = $this;
        }
    }

    public static function getInstance() {
        return self::$instance;
    }

    public function enable() {
        $this->enabled = true;
    }

    public function depositItems($block, $itemStack) {
        $remaining = [];
        if ($this->isContainer($block)) {
            $remaining = $this->addItemToContainer($block, $itemStack);
            if (count($remaining) > 0) {
                $doubleChestBlock = $this->findAdjacentBlock($block);
                if ($doubleChestBlock !== null) {
                    $remaining = $this->addItemToContainer($doubleChestBlock, reset($remaining));
                }
            }
        }
        return $remaining;
    }

    private function isContainer($block) {
        return in_array($block, ['CHEST', 'DOUBLE_CHEST']);
    }

    private function addItemToContainer($block, $itemStack) {
        // Simulate adding itemStack to container and return remaining items
        return []; // Placeholder, here we would implement actual logic
    }

    private function findAdjacentBlock($block) {
        // Simulate finding an adjacent block of the same type
        return null; // Placeholder, return null for simplicity
    }
}

$lwc = new LWC();
$lwc->enable();
$block = 'CHEST';
$itemStack = 'ITEM';
$lwc->depositItems($block, $itemStack);
--EXPECT--
