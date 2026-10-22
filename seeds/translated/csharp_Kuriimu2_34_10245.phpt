--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/34)
--FILE--
<?php
class Command {}
class Label {
    public $Size;
}
class Button {
    public $Text;
    public $Command;
    public $Size;
}
class TableLayout {
    public $Rows = [];
}
class Panel {
    public $Content;
}
class TextForm extends Panel {
    private $_firstEntryText;
    private $_toggleButton;
    private $_toggleCommand;
    public function __construct() {
        $this->InitializeComponent();
    }
    private function InitializeComponent() {
        $this->_toggleCommand = new Command();
        $this->_firstEntryText = new Label();
        $this->_firstEntryText->Size = [-1, 300];
        $this->_toggleButton = new Button();
        $this->_toggleButton->Text = "Toggle codes";
        $this->_toggleButton->Command = $this->_toggleCommand;
        $this->_toggleButton->Size = [-1, 30];
        $layout = new TableLayout();
        $layout->Rows[] = $this->_firstEntryText;
        $layout->Rows[] = $this->_toggleButton;
        $this->Content = $layout;
    }
}
new TextForm();
?>
--EXPECT--
