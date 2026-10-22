--TEST--
Translated seed (inferredbugs-csharp: kiota/29)
--FILE--
<?php
$package = "main";
$type = "type";
$struct = "struct";
$imports = [];
$imports["i5bda920e7ace9d5445a4ab998b248741f78d1219c76fbec57ddf13651f485ee4"] = "Go.util";
$imports["ib040b76fd8c2f056725723d35361c053f34e28d2ff1828ce830f3e00e807a59b"] = "project/graph";
ksort($imports);
$out = "package $package\n\n";
$out .= "$type parentClass $struct {\n}\n\n";
if ($imports) {
    $out .= "import (\n";
    foreach ($imports as $alias => $path) {
        $out .= "\t$alias \"$path\"\n";
    }
    $out .= ")\n";
}
echo $out;
--EXPECT--
