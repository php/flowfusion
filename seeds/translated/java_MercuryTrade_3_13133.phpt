--TEST--
Translated seed (inferredbugs-java: MercuryTrade/3)
--FILE--
<?php
$LOCAL_UPDATER_PATH = getenv('USERPROFILE') . "\\AppData\\Local\\MercuryTrade\\local-updater.jar";
$updateReady = false;

function doUpdate($path) {
    global $LOCAL_UPDATER_PATH;
    exec("java -jar " . escapeshellarg($LOCAL_UPDATER_PATH) . " " . escapeshellarg($path));
    exit(0);
}

$updatePanelVisible = false;

// Simulating mouse events
$mouseEntered = function() use (&$updatePanelVisible) {
    if (!$updatePanelVisible) {
        $updatePanelVisible = true;
        // Simulate animation start
    }
};

$mouseExited = function() use (&$updatePanelVisible) {
    if ($updatePanelVisible) {
        $updatePanelVisible = false;
        // Simulate animation collapse
    }
};

$restartLabelMouseClicked = function() {
    $path = 'dummy_path'; // Simulate getting a path
    doUpdate($path);
};

// Simulate mouse entering and exiting
$mouseEntered();
$mouseExited();

// Simulate clicking the restart label
$restartLabelMouseClicked();
--EXPECT--
