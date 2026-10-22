--TEST--
Translated seed (inferredbugs-csharp: crayon/16)
--FILE--
<?php
$showLibStack = (bool)($_SERVER['argv'][1] ?? false);
$useOutputPrefixes = (bool)($_SERVER['argv'][2] ?? false);
$args = array_slice($_SERVER['argv'], 3);

$bundle = [
    'ByteCode' => str_repeat("A", 1),
    'ResourceDB' => [
        'ResourceManifestFile' => ['TextContent' => ""],
        'ImageResourceManifestFile' => ['TextContent' => ""],
    ],
];

$byteCode = $bundle['ByteCode'];
$resourceManifest = $bundle['ResourceDB']['ResourceManifestFile']['TextContent'];
$imageManifest = $bundle['ResourceDB']['ImageResourceManifestFile']['TextContent'];

$vm = [
    'byteCode' => $byteCode,
    'resourceManifest' => $resourceManifest,
    'imageManifest' => $imageManifest,
    'args' => $args,
    'environment' => [
        'stdoutPrefix' => $useOutputPrefixes ? "STDOUT" : null,
        'stacktracePrefix' => $useOutputPrefixes ? "STACKTRACE" : null,
    ],
    'libStackTrace' => $showLibStack,
];

if ($vm['libStackTrace']) {
    $vm['environment']['stacktracePrefix'] = $vm['environment']['stacktracePrefix'] ?? "STACKTRACE";
}

for ($i = 0, $n = strlen($vm['byteCode']); $i < $n; $i++) {
    $c = $vm['byteCode'][$i];
    if ($c === "\0") {
        break;
    }
}

echo "";
?>
--EXPECT--
