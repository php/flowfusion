--TEST--
Translated seed (inferredbugs-csharp: TCPChat/1)
--FILE--
<?php
$rooms = [];
$downloadingFiles = [];
$postedFiles = [];
$model = null;

try {
    if ($model === null) {
        throw new InvalidArgumentException("model do not inited yet");
    }
} catch (Throwable $e) {
}

if ($model !== null) {
    throw new RuntimeException("model already inited");
}
$model = new stdClass();
$model->Rooms = [];
$model->DownloadingFiles = [];
$model->PostedFiles = [];
$model->User = new stdClass();
$model->User->NickColor = [255, 0, 0];

if ($model === null) {
    throw new RuntimeException("model not yet inited");
}
$model = null;

if ($model !== null) {
    throw new RuntimeException("unexpected state");
}

$events = [
    'RoomRefreshed' => null,
    'Connected' => null,
    'ReceiveRegistrationResponse' => null,
    'ReceiveMessage' => null,
    'AsyncError' => null,
    'RoomOpened' => null,
    'RoomClosed' => null,
    'DownloadProgress' => null,
    'PostedFileDeleted' => null,
    'PluginLoaded' => null,
    'PluginUnloading' => null,
];

$events['ReceiveMessage'] = function ($sender, $args) use (&$events) {
    if ($events['AsyncError'] !== null) {
        ($events['AsyncError'])($sender, $args);
    }
};

$events['AsyncError'] = function ($sender, $args) {
};

foreach ($events as $name => $handler) {
    if ($handler !== null) {
        $handler(null, (object)['Type' => 'System', 'Message' => $name]);
    }
}

$dict = [];
$dict['a'] = 1;
$dict['b'] = 2;
unset($dict['a']);
$dict['a'] = 3;

$list = [];
$list[] = 'x';
$list[] = 'y';
array_pop($list);
$list[] = 'z';

echo "";
?>
--EXPECT--
