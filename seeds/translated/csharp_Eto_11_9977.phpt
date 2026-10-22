--TEST--
Translated seed (inferredbugs-csharp: Eto/11)
--FILE--
<?php

class MainForm {
    private $eventLog;
    private $contentContainer;

    public function __construct() {
        $this->title = "Test Application";
        $this->clientSize = [900, 650];
        
        $this->eventLog = $this->createTextArea();
        $this->contentContainer = $this->createPanel();

        $this->createMainContent();
    }

    private function createTextArea() {
        return [
            'size' => [100, 100],
            'readOnly' => true,
            'wrap' => false,
            'text' => ''
        ];
    }

    private function createPanel() {
        return [];
    }

    private function createMainContent() {
        $splitter = [
            'position' => 200,
            'fixedPanel' => 'Panel1',
            'panel1' => $this->createSectionList(),
            'panel2' => $this->createRightPane(),
        ];
    }

    private function createSectionList() {
        return $this->createPanel();
    }

    private function createRightPane() {
        $splitter = [
            'orientation' => 'vertical',
            'position' => 500,
            'fixedPanel' => 'Panel2',
            'panel1' => $this->contentContainer,
            'panel2' => $this->createEventLogSection(),
        ];
        return $splitter;
    }

    private function createEventLogSection() {
        $layout = [
            'horizontal' => [
                'controls' => [$this->eventLog, $this->createClearButton()]
            ]
        ];
        return $layout;
    }

    private function createClearButton() {
        return [
            'text' => 'Clear',
            'onClick' => function() {
                $this->eventLog['text'] = '';
            }
        ];
    }

    public function run() {
        // Simulate the application running without actual UI framework
        // Trigger the clear button click to demonstrate the behavior
        ($this->createClearButton()['onClick'])();
        echo "Event Log: " . $this->eventLog['text'] . PHP_EOL;
    }
}

$form = new MainForm();
$form->run();
--EXPECT--
