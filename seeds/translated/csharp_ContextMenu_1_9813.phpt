--TEST--
Translated seed (inferredbugs-csharp: ContextMenu/1)
--FILE--
<?php

class SideActionBarSamplePage {
    private $sampleList;

    public function __construct() {
        $this->sampleList = range(0, 299);
    }

    public function onClicked($commandParameter) {
        echo "$commandParameter clicked\n";

        $this->forceClose();
    }

    private function forceClose() {
        // Simulate the closing action
        echo "Force close action executed\n";
    }
}

$page = new SideActionBarSamplePage();
$page->onClicked(5);  // Simulating a button click with a command parameter of 5

?>
--EXPECT--
