--TEST--
Translated seed (inferredbugs-csharp: sledge/8)
--FILE--
<?php
$viewportData = [];
$sceneData = [];
$activeScene = null;
$initialised = false;

$viewport = (object)['w' => 1, 'h' => 1];
$scene = (object)['trackChanges' => true, 'array' => (object)['changes' => 0, 'disposed' => false]];

$sceneData[spl_object_id($scene)] = null;
$activeScene = $scene;

for ($i = 0; $i < 3; $i++) {
    if ($activeScene === null) continue;

    $vid = spl_object_id($viewport);
    if (!isset($viewportData[$vid])) {
        $viewportData[$vid] = (object)[
            'fb' => (object)['w' => $viewport->w, 'h' => $viewport->h, 'disposed' => false],
            'initialised' => false,
            'width' => 0,
            'height' => 0
        ];
    }
    $vpData = $viewportData[$vid];

    $sid = spl_object_id($activeScene);
    if (!isset($sceneData[$sid]) || $sceneData[$sid] === null) {
        $sceneData[$sid] = (object)['scene' => $activeScene, 'array' => (object)['changes' => 0, 'disposed' => false]];
    }
    $scData = $sceneData[$sid];

    if (!$initialised) {
        $initialised = true;
    }

    if (!$vpData->initialised) {
        $vpData->initialised = true;
    }
    if ($vpData->width !== $viewport->w || $vpData->height !== $viewport->h) {
        $vpData->fb->w = $viewport->w;
        $vpData->fb->h = $viewport->h;
        $vpData->width = $viewport->w;
        $vpData->height = $viewport->h;
    }

    $scData->array->changes++;
    $vpData->fb->rendered = true;
}

if ($activeScene !== null) {
    $sid = spl_object_id($activeScene);
    if (isset($sceneData[$sid]) && $sceneData[$sid] !== null) {
        $sceneData[$sid]->array->disposed = true;
    }
    unset($sceneData[$sid]);
    $activeScene = null;
}
?>
--EXPECT--
