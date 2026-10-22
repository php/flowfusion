--TEST--
Translated seed (inferredbugs-csharp: SqlSugar/3)
--FILE--
<?php
class Student {
    public string $Name = "Alice";
}

$it = new Student();
$exp = fn($x) => $x->Name;
$expContext = ['type' => 'FieldMultiple', 'result' => $exp($it)];
$selectorValue = (string)$expContext['result'];
echo $selectorValue;
?>
--EXPECT--
