--TEST--
Translated seed (inferredbugs-csharp: vst.net/1)
--FILE--
<?php
$parameters = [
    10 => ['default' => 0.25, 'flags' => 1, 'paramId' => 10, 'shortTitle' => 'A', 'stepCount' => 4, 'title' => 'Alpha', 'unitId' => 0, 'units' => 'dB', 'value' => 0.25],
    20 => ['default' => 0.5, 'flags' => 2, 'paramId' => 20, 'shortTitle' => 'B', 'stepCount' => 8, 'title' => 'Beta', 'unitId' => 1, 'units' => '%', 'value' => 0.5],
];

$componentHandler = null;
$componentHandler2 = null;
$componentHandler3 = null;

$paramId = 10;
if (array_key_exists($paramId, $parameters)) {
    $p = $parameters[$paramId];
    $info = [
        'DefaultNormalizedValue' => $p['default'],
        'Flags' => $p['flags'],
        'ParamId' => $p['paramId'],
        'ShortTitle' => $p['shortTitle'],
        'StepCount' => $p['stepCount'],
        'Title' => $p['title'],
        'UnitId' => $p['unitId'],
        'Units' => $p['units'],
    ];
    $s = (string)$p['value'];
    $v = is_numeric($s) ? (float)$s : 0.0;
    $parameters[$paramId]['value'] = $v;
    $plain = $parameters[$paramId]['value'] * 100.0;
    $norm = $plain / 100.0;
    $current = $parameters[$paramId]['value'];
    $parameters[$paramId]['value'] = $norm;
}

$componentHandler = null;
$componentHandler2 = null;
$componentHandler3 = null;
echo "";
?>
--EXPECT--
