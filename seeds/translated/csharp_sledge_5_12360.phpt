--TEST--
Translated seed (inferredbugs-csharp: sledge/5)
--FILE--
<?php
$sceneData = [];
$viewportData = [];
$activeScene = null;
$initialised = false;

$createScene = function () use (&$sceneData) {
    $scene = (object)['trackChanges' => true];
    $sceneData[spl_object_id($scene)] = null;
    return $scene;
};

$createViewport = function ($w, $h, $camera) {
    return (object)['control' => (object)['width' => $w, 'height' => $h], 'camera' => $camera];
};

$scene1 = $createScene();
$scene2 = $createScene();
$activeScene = $scene1;

$viewport = $createViewport(640, 480, (object)['type' => 'perspective', 'position' => [-0.002, -0.002, -0.002]]);
$viewportKey = spl_object_id($viewport);

if (!isset($viewportData[$viewportKey])) {
    $viewportData[$viewportKey] = (object)['framebuffer' => ['size' => [640, 480]], 'initialised' => false, 'width' => 0, 'height' => 0];
}

$vpData = $viewportData[$viewportKey];
if (!$vpData->initialised) {
    $vpData->initialised = true;
}
if ($vpData->width !== $viewport->control->width || $vpData->height !== $viewport->control->height) {
    $vpData->framebuffer['size'] = [$viewport->control->width, $viewport->control->height];
}

if ($activeScene !== null) {
    $sceneKey = spl_object_id($activeScene);
    if (!array_key_exists($sceneKey, $sceneData) || $sceneData[$sceneKey] === null) {
        $sceneData[$sceneKey] = (object)['scene' => $activeScene, 'array' => (object)['disposed' => false]];
    }
    $scData = $sceneData[$sceneKey];
    $viewport->camera->position[0] -= -0.002;
    $viewport->camera->position[1] -= -0.002;
    $viewport->camera->position[2] -= -0.002;
}

$activeScene = null;
$sceneKey = spl_object_id($scene1);
if (array_key_exists($sceneKey, $sceneData)) {
    $sceneData[$sceneKey] = null;
}
unset($sceneData[$sceneKey]);

$activeScene = $scene2;
$sceneKey = spl_object_id($activeScene);
if (!array_key_exists($sceneKey, $sceneData) || $sceneData[$sceneKey] === null) {
    $sceneData[$sceneKey] = (object)['scene' => $activeScene, 'array' => (object)['disposed' => false]];
}

echo "";
?>
--EXPECT--
