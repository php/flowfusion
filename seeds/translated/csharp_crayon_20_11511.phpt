--TEST--
Translated seed (inferredbugs-csharp: crayon/20)
--FILE--
<?php
$cbx = $argv[1] ?? '';
$bundle = [];
if ($cbx !== '' && is_file($cbx)) {
    $data = file_get_contents($cbx);
    $parts = explode("\n", $data, 3);
    $bundle['byteCode'] = $parts[0] ?? '';
    $bundle['resourceManifest'] = $parts[1] ?? '';
    $bundle['imageManifest'] = $parts[2] ?? '';
    $bundle['flatNames'] = [];
    $bundle['flatFiles'] = [];
    if (preg_match_all('/^res:([^\n]+)\n([\s\S]*?)(?=^res:|\z)/m', $data, $m, PREG_SET_ORDER)) {
        foreach ($m as $r) {
            $bundle['flatNames'][] = 'res/' . $r[1];
            $bundle['flatFiles'][] = $r[2];
        }
    }
} else {
    $bundle = [
        'byteCode' => '',
        'resourceManifest' => '',
        'imageManifest' => '',
        'flatNames' => [],
        'flatFiles' => [],
    ];
}
$args = array_slice($argv, 2);
$ext = $argv[2] ?? '';
$out = [
    'args' => $args,
    'ext' => $ext,
    'showLibStack' => false,
    'useOutputPrefixes' => false,
    'realTimePrint' => false,
];
echo json_encode($out, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
