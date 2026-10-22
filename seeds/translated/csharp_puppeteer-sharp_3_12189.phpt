--TEST--
Translated seed (inferredbugs-csharp: puppeteer-sharp/3)
--FILE--
<?php
$closed = false;
$closeReason = null;
$callbacks = [];
$sessions = [];
$lastId = 0;
$delay = 1;
$messages = [
    '{"method":"Target.attachedToTarget","params":{"sessionId":"s1","targetInfo":{"type":"page"}}}',
    '{"id":1,"result":{"ok":true}}',
    '{"method":"Target.detachedFromTarget","params":{"sessionId":"s1"}}',
    '{"method":"event","params":{"x":1}}',
    'not json',
];

foreach ($messages as $response) {
    if ($closed) {
        continue;
    }
    if ($response !== '' && $delay > 0) {
        usleep($delay * 1000);
    }
    $obj = json_decode($response);
    if (json_last_error() !== JSON_ERROR_NONE) {
        continue;
    }
    $method = $obj->method ?? null;
    $params = $obj->params ?? null;

    if ($method === 'Target.attachedToTarget') {
        $sessionId = $params->sessionId ?? null;
        if ($sessionId !== null) {
            $sessions[$sessionId] = ['type' => $params->targetInfo->type ?? null, 'closed' => false];
        }
    } elseif ($method === 'Target.detachedFromTarget') {
        $sessionId = $params->sessionId ?? null;
        if ($sessionId !== null && isset($sessions[$sessionId]) && !$sessions[$sessionId]['closed']) {
            $sessions[$sessionId]['closed'] = true;
        }
    }

    if (!empty($obj->sessionId)) {
        $sessionId = $obj->sessionId;
        if (isset($sessions[$sessionId])) {
            $sessions[$sessionId]['last'] = $obj;
        }
    } elseif (isset($obj->id)) {
        unset($callbacks[$obj->id]);
    } else {
        $event = ['messageId' => $method, 'messageData' => $params];
    }
}

$lastId++;
$callbacks[$lastId] = ['method' => 'test'];
unset($callbacks[$lastId]);
$closeReason = 'Connection disposed';
$closed = true;
$sessions = [];
$callbacks = [];
echo "";
?>
--EXPECT--
