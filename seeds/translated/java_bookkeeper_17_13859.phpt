--TEST--
Translated seed (inferredbugs-java: bookkeeper/17)
--FILE--
<?php

$topic = "test_topic";
$msg = "test_message";
$host2Channel = [];
$closed = false;

function publish($topic, $msg) {
    global $host2Channel, $closed;

    if ($closed) return;

    if (isset($host2Channel[$topic])) {
        doPublish($topic, $msg, $host2Channel[$topic]);
    } else {
        $host = "default_host";
        if (isset($host2Channel[$host])) {
            doPublish($topic, $msg, $host2Channel[$host]);
            return;
        }
        doConnect($topic, $msg, $host);
    }
}

function doPublish($topic, $msg, $channel) {
    $txnId = rand(1, 1000);
    $request = [
        'topic' => $topic,
        'msg' => $msg,
        'txnId' => $txnId,
    ];
    // Simulate sending the request over channel
}

function doConnect($topic, $msg, $host) {
    global $host2Channel;
    $host2Channel[$topic] = "channel_for_$host"; // Simulate channel creation
    publish($topic, $msg);
}

publish($topic, $msg);
--EXPECT--
