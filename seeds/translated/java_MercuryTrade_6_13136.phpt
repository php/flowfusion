--TEST--
Translated seed (inferredbugs-java: MercuryTrade/6)
--FILE--
<?php

$HISTORY_FILE = getenv("USERPROFILE") . "\\AppData\\Local\\MercuryTrade\\history.json";
$messages = [];
$curIndex = 0;

if (file_exists($HISTORY_FILE)) {
    $json = json_decode(file_get_contents($HISTORY_FILE), true);
    $messages = $json['messages'] ?? [];
} else {
    file_put_contents($HISTORY_FILE, json_encode(['messages' => []]));
}

function addMessage($message) {
    global $HISTORY_FILE;
    $json = json_decode(file_get_contents($HISTORY_FILE), true);
    array_unshift($json['messages'], $message);
    file_put_contents($HISTORY_FILE, json_encode($json));
}

function fetchNext($messagesCount) {
    global $messages, $curIndex;
    $chunk = [];
    if (($curIndex + $messagesCount) < count($messages)) {
        $chunk = array_slice($messages, $curIndex, $messagesCount);
        $curIndex += $messagesCount;
    } elseif ((count($messages) - $curIndex) > 0) {
        $availableCount = count($messages) - $curIndex;
        $chunk = array_slice($messages, $curIndex, $availableCount);
        $curIndex += $availableCount;
    }
    return $chunk;
}

addMessage("Test message 1");
$nextMessages = fetchNext(1);
--EXPECT--
