--TEST--
Translated seed (inferredbugs-csharp: Eto/10)
--FILE--
<?php

class MainForm {
    private $eventLog;
    private $contentContainer;

    public function __construct() {
        $this->eventLog = $this->createTextArea();
        $this->contentContainer = $this->createPanel();
        $this->initialize();
    }

    private function createTextArea() {
        return '';
    }

    private function createPanel() {
        return [];
    }

    private function initialize() {
        $this->onShow();
        $this->mainContent();
    }

    private function onShow() {
        // Simulate focus on show
        // In PHP, we just need to demonstrate the structure
    }

    private function mainContent() {
        $splitter = $this->createSplitter();
        $this->contentContainer[] = $splitter;
    }

    private function createSplitter() {
        return [
            'panel1' => $this->createSectionList(),
            'panel2' => $this->rightPane()
        ];
    }

    private function createSectionList() {
        return 'Section List';
    }

    private function rightPane() {
        return [
            'panel1' => $this->contentContainer,
            'panel2' => $this->eventLogSection()
        ];
    }

    private function eventLogSection() {
        return [
            'horizontal' => [
                'eventLog' => $this->eventLog,
                'clearButton' => $this->clearButton()
            ]
        ];
    }

    private function clearButton() {
        return 'Clear Button';
    }
}

function run() {
    $mainForm = new MainForm();
}

run();
--EXPECT--
