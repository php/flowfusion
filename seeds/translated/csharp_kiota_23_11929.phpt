--TEST--
Translated seed (inferredbugs-csharp: kiota/23)
--FILE--
<?php
$package = "main";
$type = "type";
$struct = "struct";
$someParent = "SomeParent";
$import = "import (";
$u1 = "ib040b76fd8c2f056725723d35361c053f34e28d2ff1828ce830f3e00e807a59b \"project/graph\"";
$u2 = "i5bda920e7ace9d5445a4ab998b248741f78d1219c76fbec57ddf13651f485ee4 \"Go.util\"";

$out = "package $package\n\n";
$out .= "$type parentClass $struct {}\n\n";
$out .= "$type childClass $struct {\n\t$someParent\n}\n\n";
$out .= "$import\n\t$u1\n\t$u2\n)\n";

echo $out;
--EXPECT--
