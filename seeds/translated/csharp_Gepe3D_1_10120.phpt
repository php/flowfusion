--TEST--
Translated seed (inferredbugs-csharp: Gepe3D/1)
--FILE--
<?php
$w = 1600;
$h = 900;
$title = "Gepe3D";
$scene = ["name" => "World", "state" => ["keyboard" => null, "mouse" => null]];
$keyboardState = ["escape" => false];
$mouseState = ["x" => 0, "y" => 0, "buttons" => []];
for ($i = 0; $i < 3; $i++) {
    $keyboardState["escape"] = ($i === 1);
    $scene["state"]["keyboard"] = $keyboardState;
    $scene["state"]["mouse"] = $mouseState;
    if (!empty($scene["state"]["keyboard"]["escape"])) {
        break;
    }
    $scene["update"] = ($scene["update"] ?? 0) + 1;
    $scene["render"] = ($scene["render"] ?? 0) + 1;
}
echo $title . " " . $w . "x" . $h . " " . $scene["name"] . PHP_EOL;
--EXPECT--
