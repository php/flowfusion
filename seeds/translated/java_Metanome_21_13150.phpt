--TEST--
Translated seed (inferredbugs-java: Metanome/21)
--FILE--
<?php

class BasePage {}

class ResultsPage {
    public $basePage;
    public $runningIndicator;
    public $progressBar;
    public $algorithmLabel;
    public $executionTimePanel;
    public $messageReceiver;

    public function __construct($parent) {
        $this->basePage = $parent;
    }

    public function startPolling($flag) {
        if ($flag) {
            $this->runningIndicator = true;
            $this->progressBar = true;
            $this->algorithmLabel = true;
            $this->executionTimePanel = true;
        }
    }

    public function updateOnError($message) {
        $this->messageReceiver->setError(true);
        // reset widget count
    }

    public function updateOnSuccess($execution) {
        // update widget count and state
    }

    public function getWidgetCount() {
        return 2; // mock value for demonstration
    }

    public function getWidget($index) {
        return new TabLayoutPanel(); // mock return
    }
}

class TabLayoutPanel {
    public function getWidgetCount() {
        return 2; // mock value for demonstration
    }
}

class TabWrapper {
    private $inError = false;

    public function setError($status) {
        $this->inError = $status;
    }

    public function isInError() {
        return $this->inError;
    }
}

function resetDatabaseSync() {}

resetDatabaseSync();

$parent = new BasePage();
$page = new ResultsPage($parent);

$page->startPolling(true);
$page->messageReceiver = new TabWrapper();
$page->updateOnError("message");

$page->updateOnSuccess(null);

echo "Done";
--EXPECT--
