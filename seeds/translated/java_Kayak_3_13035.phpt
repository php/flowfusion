--TEST--
Translated seed (inferredbugs-java: Kayak/3)
--FILE--
<?php

$showOnStartup = true;

function toggleShowOnStartup() {
    global $showOnStartup;
    $showOnStartup = !$showOnStartup;
}

$toggle = true; // simulating checkbox toggle
if ($toggle) {
    toggleShowOnStartup();
}

$showPersistenceType = $showOnStartup ? "PERSISTENCE_ALWAYS" : "PERSISTENCE_NEVER";
echo $showPersistenceType;
--EXPECT--
