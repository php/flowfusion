--TEST--
Translated seed (inferredbugs-csharp: azure-functions-openapi-extension/2)
--FILE--
<?php
$schemas = [];
$name = "sample";
$attr = (object)[
    "Nullable" => true,
    "Default" => 18446744073709551615,
    "Description" => "desc",
];
$dataType = "string";
$dataFormat = "int64";
if (!array_key_exists($name, $schemas)) {
    $schema = [
        "Title" => "title",
        "Type" => $dataType,
        "Format" => $dataFormat,
    ];
    if ($attr !== null) {
        if ($dataType !== "object") {
            $schema["Nullable"] = (bool)$attr->Nullable;
            $d = $attr->Default;
            if ($d !== null) {
                if (is_bool($d)) $schema["Default"] = $d ? "true" : "false";
                elseif (is_int($d)) $schema["Default"] = (string)$d;
                elseif (is_float($d)) $schema["Default"] = (string)$d;
                elseif (is_string($d)) $schema["Default"] = $d;
                else $schema["Default"] = (string)$d;
            }
        }
        $schema["Description"] = $attr->Description;
    }
    $schemas[$name] = $schema;
}
echo "";
?>
--EXPECT--
