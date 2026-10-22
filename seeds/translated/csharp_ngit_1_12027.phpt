--TEST--
Translated seed (inferredbugs-csharp: ngit/1)
--FILE--
<?php
$mode = null;
$paths = [];
$ref = 'HEAD';
foreach (array_slice($argv, 1) as $a) {
    if ($a === '--soft' || $a === '--mixed' || $a === '--hard') {
        $mode = $a;
    } else {
        $paths[] = $a;
    }
}
if ($mode !== null && $paths) {
    $paths = [];
}
$repo = [
    'state' => 'MERGING',
    'head' => 'deadbeef',
    'origHead' => null,
    'squash' => 'msg',
    'mergeHeads' => ['x'],
    'mergeMsg' => 'y',
    'cherryPickHead' => 'z',
];
$commit = ['id' => 'cafebabe', 'tree' => ['a.txt' => '1', 'b.txt' => '2']];
if ($paths) {
    foreach ($paths as $p) {
        if (!array_key_exists($p, $commit['tree'])) {
            unset($repo['index'][$p]);
        } else {
            $repo['index'][$p] = $commit['tree'][$p];
        }
    }
    exit(0);
}
$oldHead = $repo['head'];
$repo['head'] = $commit['id'];
if ($oldHead !== null) {
    $repo['origHead'] = $oldHead;
}
if ($mode === '--hard') {
    $repo['index'] = $commit['tree'];
} elseif ($mode === '--mixed') {
    $repo['index'] = $commit['tree'];
} elseif ($mode === '--soft' || $mode === null) {
}
if ($mode !== '--soft') {
    if ($repo['state'] === 'MERGING' || $repo['state'] === 'MERGING_RESOLVED') {
        $repo['mergeHeads'] = null;
        $repo['mergeMsg'] = null;
    } elseif ($repo['state'] === 'CHERRY_PICKING' || $repo['state'] === 'CHERRY_PICKING_RESOLVED') {
        $repo['cherryPickHead'] = null;
        $repo['mergeMsg'] = null;
    } elseif ($repo['squash'] !== null) {
        $repo['squash'] = null;
    }
}
echo $repo['head'], "\n";
?>
--EXPECT--
