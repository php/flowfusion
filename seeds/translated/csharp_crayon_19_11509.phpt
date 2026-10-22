--TEST--
Translated seed (inferredbugs-csharp: crayon/19)
--FILE--
<?php
$req = [
    'realTimePrint' => false,
    'showLibStack' => false,
    'useOutputPrefixes' => true,
    'args' => ['a', '', '0', '1', 'false', 'null', '😀'],
    'cbxBundle' => [
        'ByteCode' => str_repeat("ABCD", 1024),
        'ResourceDB' => [
            'ResourceManifestFile' => ['TextContent' => "res1\nres2\n"],
            'ImageResourceManifestFile' => ['TextContent' => "img1\nimg2\n"],
            'FlatFileNames' => ['res/x', 'res/y', 'res/z'],
            'FlatFiles' => [str_repeat("\0", 3), str_repeat("x", 0), random_bytes(7)],
        ],
    ],
    'extArgsJson' => '{"k":"v","n":0,"a":[1,2,3]}',
];

$bundle = $req['cbxBundle'];
$byteCode = $bundle['ByteCode'];
$resourceManifest = $bundle['ResourceDB']['ResourceManifestFile']['TextContent'];
$imageManifest = $bundle['ResourceDB']['ImageResourceManifestFile']['TextContent'];
$runtimeArgs = $req['args'];
$extensionArgsJson = $req['extArgsJson'];
$showOutputPrefixes = $req['useOutputPrefixes'];
$showLibStack = $req['showLibStack'];

$vm = [
    'byteCodeHash' => hash('sha256', $byteCode . $resourceManifest . $imageManifest),
    'args' => $runtimeArgs,
    'resourceNames' => $bundle['ResourceDB']['FlatFileNames'],
    'resourceBytes' => $bundle['ResourceDB']['FlatFiles'],
    'payload' => $extensionArgsJson,
    'stdoutPrefix' => $showOutputPrefixes ? 'STDOUT' : null,
    'stacktracePrefix' => $showOutputPrefixes ? 'STACKTRACE' : null,
    'libStack' => $showLibStack,
];

$out = [];
foreach ($vm['resourceNames'] as $i => $name) {
    $out[$name] = strlen($vm['resourceBytes'][$i]);
}
foreach ($vm['args'] as $i => $arg) {
    $out["arg$i"] = $arg;
}
$out['payload'] = json_decode($vm['payload'], true);
$out['hash'] = $vm['byteCodeHash'];
$out['prefixes'] = [$vm['stdoutPrefix'], $vm['stacktracePrefix']];
$out['libStack'] = $vm['libStack'];

echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
