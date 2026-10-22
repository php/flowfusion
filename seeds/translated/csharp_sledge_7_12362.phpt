--TEST--
Translated seed (inferredbugs-csharp: sledge/7)
--FILE--
<?php
$store = [];
$active = null;
$viewport = (object)['w' => 1, 'h' => 1];
$scene = (object)['id' => 1, 'track' => true];

if (!isset($store[spl_object_id($viewport)])) {
    $store[spl_object_id($viewport)] = ['fb' => ['w' => $viewport->w, 'h' => $viewport->h], 'init' => false, 'width' => 0, 'height' => 0];
}
if (!isset($store['scene']) || $store['scene'] === null) {
    $store['scene'] = ['array' => ['dirty' => true, 'disposed' => false]];
}

$active = $scene;

for ($i = 0; $i < 3; $i++) {
    $vpData =& $store[spl_object_id($viewport)];
    $scData =& $store['scene'];

    if (!$vpData['init']) {
        $vpData['init'] = true;
    }
    if ($vpData['width'] !== $viewport->w || $vpData['height'] !== $viewport->h) {
        $vpData['fb']['w'] = $viewport->w;
        $vpData['fb']['h'] = $viewport->h;
        $vpData['width'] = $viewport->w;
        $vpData['height'] = $viewport->h;
    }

    $scData['array']['dirty'] = false;
    $vpData['fb']['bound'] = true;
    $vpData['fb']['cleared'] = true;
    $vpData['fb']['bound'] = false;
    $vpData['fb']['rendered'] = true;
}

if ($active === $scene) {
    unset($store['scene']);
    $active = null;
}

echo "";
?>
--EXPECT--
