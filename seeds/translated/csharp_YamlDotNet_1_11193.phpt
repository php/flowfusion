--TEST--
Translated seed (inferredbugs-csharp: YamlDotNet/1)
--FILE--
<?php
$items = [
    "tag:yaml.org,2002:map" => [],
    "tag:yaml.org,2002:bool" => true,
    "tag:yaml.org,2002:float" => 1.5,
    "tag:yaml.org,2002:int" => 2147483647,
    "tag:yaml.org,2002:str" => "x",
    "tag:yaml.org,2002:timestamp" => new DateTimeImmutable("2020-01-02T03:04:05Z"),
];

foreach ($items as $tag => $v) {
    $t = null;
    if ($tag === "tag:yaml.org,2002:map") $t = "array";
    elseif ($tag === "tag:yaml.org,2002:bool") $t = "bool";
    elseif ($tag === "tag:yaml.org,2002:float") $t = "float";
    elseif ($tag === "tag:yaml.org,2002:int") $t = "int";
    elseif ($tag === "tag:yaml.org,2002:str") $t = "string";
    elseif ($tag === "tag:yaml.org,2002:timestamp") $t = DateTimeImmutable::class;
    if ($t !== null) {
        if ($t === "array") { $x = []; }
        elseif ($t === "bool") { $x = (bool)$v; }
        elseif ($t === "float") { $x = (float)$v; }
        elseif ($t === "int") { $x = (int)$v; }
        elseif ($t === "string") { $x = (string)$v; }
        else { $x = new $t($v->format(DateTimeInterface::ATOM)); }
    }
}

$a = [];
$a["self"] = &$a;
$b = ["n" => "null", "i" => "1_234_567", "f" => "3.14159", "s" => "hello", "t" => "2020-01-02 03:04:05"];
$c = [];
foreach ($b as $k => $v) {
    if ($v === "null") $c[$k] = null;
    elseif ($k === "i") $c[$k] = (int)str_replace("_", "", $v);
    elseif ($k === "f") $c[$k] = (float)str_replace("_", "", $v);
    elseif ($k === "t") $c[$k] = new DateTimeImmutable($v);
    else $c[$k] = $v;
}
$d = [];
$d[] = $c;
$d[] = $a;
echo "";
?>
--EXPECT--
