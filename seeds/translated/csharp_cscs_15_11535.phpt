--TEST--
Translated seed (inferredbugs-csharp: cscs/15)
--FILE--
<?php
$allClasses = [];
$className = "Base";
$allClasses[$className] = ["props" => ["x" => 1, "y" => 2], "methods" => []];
$allClasses["Child"] = ["props" => $allClasses["Base"]["props"], "methods" => $allClasses["Base"]["methods"]];
$allClasses["Child"]["props"]["z"] = 3;

$instance = ["class" => "Child", "props" => $allClasses["Child"]["props"]];
$instance["props"]["x"] = 10;
$instance["props"]["arr"] = [1, 2, 3];
$instance["props"]["map"] = ["a" => 1];

$copy = $instance["props"];
$copy["arr"][1] = 99;
$copy["map"]["b"] = 2;

$tokens = [];
$lines = ["a\tb\tc", "d\te\tf", "g\th\ti"];
for ($i = 0; $i < count($lines); $i++) {
    $tokens[] = explode("\t", $lines[$i]);
}

$hash = [];
foreach ($tokens as $i => $row) {
    $hash[$row[0]] = $i;
    if (isset($row[1]) && $row[1] !== "") {
        $hash[$row[1]] = $i;
    }
}

$a = [1, 2, 3];
$b = $a;
$b[] = 4;
array_splice($a, 1, 1);
$exists = array_key_exists("b", $hash);
$type = gettype($instance["props"]["arr"]);
$size = is_array($instance["props"]["arr"]) ? count($instance["props"]["arr"]) : strlen((string)$instance["props"]["arr"]);
$str = (string)($instance["props"]["x"] + $size + ($exists ? 1 : 0));
$int = (int)$str;
$bool = filter_var("true", FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
$decimal = (string)floatval("1.25e2");

echo $int, "\n";
echo $bool, "\n";
echo $decimal, "\n";
echo $type, "\n";
echo count($a), "\n";
echo count($b), "\n";
echo $copy["arr"][1], "\n";
echo $instance["props"]["arr"][1], "\n";
?>
--EXPECT--
