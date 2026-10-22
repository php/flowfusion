--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/2)
--FILE--
<?php
$dir = __DIR__;
$path = $dir . '/../../../../MyWindow.cs';
$code = @file_get_contents($path);
if ($code === false) {
    $code = "public class MyWindow { public int X {get;set;} public int Y {get;set;} public string Name {get;set;} }";
}
if (preg_match('/class\s+([A-Za-z_][A-Za-z0-9_]*)/', $code, $m)) {
    $class = $m[1];
} else {
    $class = 'MyWindow';
}
if (!class_exists($class, false)) {
    eval('class ' . $class . ' { public $X = 0; public $Y = 0; public $Name = ""; }');
}
$obj = new $class();
$ref = new ReflectionObject($obj);
$out = '';
foreach ($ref->getProperties() as $p) {
    $p->setAccessible(true);
    $out .= $p->getName() . ':' . var_export($p->getValue($obj), true) . "\n";
}
echo $out;
?>
--EXPECT--
