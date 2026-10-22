--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/7)
--FILE--
<?php
class Editor {
    public function run(): void {
        $s = "TerminalGuiDesigner";
        $a = str_split($s);
        $b = implode("", array_reverse($a));
        $c = strtolower(strrev($b));
        echo substr($c, 0, 0);
    }
}

$editor = new Editor();
$editor->run();
?>
--EXPECT--
