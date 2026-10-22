--TEST--
Translated seed (inferredbugs-csharp: azure-functions-openapi-extension/4)
--FILE--
<?php
$schemas = [];
$name = "example";
$dataType = "object";
$dataFormat = "json";
$attributes = [
    ["kind" => "property", "nullable" => true, "default" => 123, "description" => "desc"],
    ["kind" => "visibility", "visibility" => "internal"],
    ["kind" => "validation", "rule" => "required"],
];

if (!array_key_exists($name, $schemas)) {
    $schema = ["title" => "Title", "type" => $dataType, "format" => $dataFormat, "extensions" => []];
    foreach ($attributes as $attr) {
        if ($attr["kind"] === "property") {
            if ($dataType !== "object") {
                $schema["nullable"] = (bool)$attr["nullable"];
                $schema["default"] = $attr["default"];
            }
            $schema["description"] = $attr["description"];
        } elseif ($attr["kind"] === "visibility") {
            $schema["extensions"]["x-ms-visibility"] = $attr["visibility"];
        } elseif ($attr["kind"] === "validation") {
            $schema["validation"][] = $attr["rule"];
        }
    }
    $schemas[$name] = $schema;
}

$defaults = [
    true, new DateTimeImmutable("2020-01-01T00:00:00+00:00"), 1.5, 2.5, 3.5,
    "\x01\x02", 7, 8, 9, 10, 11, 12, 13, 14, 15,
    "guid-like", "plain-string"
];

$out = [];
foreach ($defaults as $d) {
    if (is_bool($d)) $out[] = $d ? "true" : "false";
    elseif ($d instanceof DateTimeInterface) $out[] = $d->format(DATE_ATOM);
    elseif (is_float($d)) $out[] = (string)$d;
    elseif (is_string($d) && strlen($d) === 2 && ord($d[0]) === 1) $out[] = bin2hex($d);
    else $out[] = (string)$d;
}

echo count($schemas) . "\n";
echo $schemas[$name]["title"] . "\n";
echo implode(",", $out) . "\n";
?>
--EXPECT--
