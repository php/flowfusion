--TEST--
Translated seed (inferredbugs-csharp: azure-functions-openapi-extension/1)
--FILE--
<?php
$schemas = [];
$name = "x";
$title = "int";
$dataType = "integer";
$dataFormat = "int32";
$attributes = [
    ["kind" => "desc", "value" => "demo"],
    ["kind" => "visibility", "value" => "public"],
];

if (!array_key_exists($name, $schemas)) {
    $schema = [
        "title" => $title,
        "type" => $dataType,
        "format" => $dataFormat,
    ];
    if ($attributes) {
        $desc = null;
        $vis = null;
        foreach ($attributes as $attr) {
            if ($attr["kind"] === "desc" && $desc === null) $desc = $attr["value"];
            if ($attr["kind"] === "visibility" && $vis === null) $vis = $attr["value"];
        }
        if ($desc !== null) $schema["description"] = $desc;
        if ($vis !== null) $schema["x-ms-visibility"] = $vis;
    }
    $schemas[$name] = $schema;
}

$parameterSchema = ["type" => "string", "format" => "uuid"];
$payloadSchema = ["type" => "object", "format" => null];

echo "";
?>
--EXPECT--
