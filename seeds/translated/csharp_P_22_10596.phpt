--TEST--
Translated seed (inferredbugs-csharp: P/22)
--FILE--
<?php
$projectName = "a b\tc";
$includeGuardMacro = "P_" . preg_replace('/\s+/', '', strtoupper($projectName)) . "_H_";
echo "#pragma once\n";
echo "#ifndef $includeGuardMacro\n";
echo "#define $includeGuardMacro\n";
echo "#include \"Prt.h\"\n\n";
echo "#ifdef __cplusplus\n";
echo "extern \"C\" {\n";
echo "#endif\n\n";
$decls = [
    ["type" => "IPDecl", "name" => "foo"],
    ["type" => "IPDecl", "name" => "bar baz"],
    ["type" => "IPDecl", "name" => "qux\tquux"],
];
$names = [];
foreach ($decls as $decl) {
    $declName = "P_" . strtoupper($projectName) . "_" . preg_replace('/\s+/', '', $decl["name"]);
    $names[] = $declName;
    echo "DECL({$decl["type"]}, {$decl["name"]}) => $declName\n";
}
echo "\n#ifdef __cplusplus\n";
echo "}\n";
echo "#endif\n\n";
echo "#endif // $includeGuardMacro\n";
?>
--EXPECT--
