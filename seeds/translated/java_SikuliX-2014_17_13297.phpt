--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/17)
--FILE--
<?php

$scriptType = 'python';
$pref = ['tabWidth' => 4, 'font' => 'Arial', 'fontSize' => 12];
$scriptIsDirty = false;

function logMessage($level, $message) {
    echo "[Level $level] $message\n";
}

function updateDocumentListeners() {
    global $scriptIsDirty;
    $scriptIsDirty = true;
    logMessage(2, "Document changed.");
}

function loadFile($filename) {
    global $scriptType, $pref;

    logMessage(3, "Loading file: $filename");
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    if ($extension !== $scriptType) {
        logMessage(1, "Unsupported file type: $extension");
        return;
    }

    // Simulate reading the file
    $fileContent = file_get_contents($filename);
    echo "File Content:\n$fileContent\n";
    updateDocumentListeners();
}

function saveFile($filename) {
    logMessage(3, "Saving file: $filename");
    // Placeholder for file-saving logic
    echo "File saved: $filename\n";
}

function setDirty($flag) {
    global $scriptIsDirty;
    $scriptIsDirty = $flag;
}

function onInsertUpdate() {
    setDirty(true);
}

function onRemoveUpdate() {
    setDirty(true);
}

function onKeyTyped($character) {
    // Simulating key typed event
    logMessage(2, "Key typed: $character");
    onInsertUpdate();
}

$testFile = "example.py";
loadFile($testFile);
onKeyTyped('a');
saveFile($testFile);
--EXPECT--
