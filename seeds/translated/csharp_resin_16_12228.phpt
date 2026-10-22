--TEST--
Translated seed (inferredbugs-csharp: resin/16)
--FILE--
<?php
$directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'resin_' . uniqid('', true);
@mkdir($directory, 0777, true);
file_put_contents($directory . DIRECTORY_SEPARATOR . '0.ix', '');

$docContainers = [];
$sync = fopen('php://memory', 'r+');

$docId = 'abc123';
$containerId = preg_replace('/\d+$/', '', $docId);
if ($containerId === '') {
    $containerId = $docId;
}

if (!isset($docContainers[$containerId])) {
    flock($sync, LOCK_EX);
    if (!isset($docContainers[$containerId])) {
        $docContainers[$containerId] = [
            'id' => $containerId,
            'docs' => [
                $docId => ['Fields' => ['DocId' => $docId, 'ContainerId' => $containerId]]
            ]
        ];
    }
    flock($sync, LOCK_UN);
}

$fields = $docContainers[$containerId]['docs'][$docId]['Fields'];
foreach ($fields as $k => $v) {
    $x = $k . '=' . $v;
}

fclose($sync);
?>
--EXPECT--
