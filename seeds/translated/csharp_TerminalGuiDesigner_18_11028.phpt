--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/18)
--FILE--
<?php
$dt = new DateTimeImmutable('now');
$s = '';
for ($i = 0; $i < 2; $i++) {
    $s .= str_repeat(' ', $i);
}
$a = [];
$a['Name'] = null;
$a['Colors'] = null;
$a[' '] = null;
$a['  '] = null;
echo "You do not have any ColorSchemes defined yet\n";
?>
--EXPECT--
