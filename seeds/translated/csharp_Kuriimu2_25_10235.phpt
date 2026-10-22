--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/25)
--FILE--
<?php
$widthText = "1";
$heightText = "1";
$offsetText = "0";
$encodings = [];
$swizzles = [];
$statusLabel = "";

$width = (int)$widthText;
$height = (int)$heightText;
$offset = (int)$offsetText;

$menu = [
    "File" => ["Open", "Close", "Extract"]
];

$baseParameterLayout = [
    ["Width:", $widthText],
    ["Height:", $heightText],
    ["Offset:", $offsetText],
    ["Encodings:", $encodings],
    ["Swizzles:", $swizzles],
];

$encodingParameterLayout = [
    "Encoding Parameters",
    "Swizzle Parameters",
];

$content = [
    "imageView" => ["BackgroundColor" => "DarkGreen"],
    "parameters" => [$baseParameterLayout, $encodingParameterLayout],
    "statusLabel" => $statusLabel,
];

echo "";
?>
--EXPECT--
