--TEST--
Translated seed (inferredbugs-csharp: NsqSharp/3)
--FILE--
<?php
$topic = 't' . (string)time();
$messages = [];
for ($i = 0; $i < 10; $i++) {
    $messages[] = 'publish_test_case';
}
$messages[] = 'bad_test_case';

$good = 0;
$failed = 0;

foreach ($messages as $msg) {
    if ($msg === 'bad_test_case') {
        $failed++;
        break;
    }
    if ($msg !== 'publish_test_case' && $msg !== 'multipublish_test_case') {
        throw new Exception("message 'action' was not correct $msg");
    }
    $good++;
}

if ($good !== 10 || $failed !== 1) {
    throw new Exception('unexpected message counts');
}

$batch = [];
for ($i = 0; $i < 10; $i++) {
    $batch[] = 'multipublish_test_case';
}

$good = 0;
$failed = 0;

foreach ($batch as $msg) {
    if ($msg === 'bad_test_case') {
        $failed++;
        break;
    }
    if ($msg !== 'multipublish_test_case' && $msg !== 'publish_test_case') {
        throw new Exception("message 'action' was not correct $msg");
    }
    $good++;
}

$queue = [];
for ($i = 0; $i < 10; $i++) {
    $queue[] = ['args' => ['test']];
}

foreach ($queue as $tx) {
    if (count($tx['args']) !== 1 || $tx['args'][0] !== 'test') {
        throw new Exception('bad async transaction');
    }
}

$tx = ['args' => ['test0', 1]];
if (count($tx['args']) !== 2 || $tx['args'][0] !== 'test0' || $tx['args'][1] !== 1) {
    throw new Exception('bad multi async transaction');
}

$heartbeatInterval = 100;
if ($heartbeatInterval < 500) {
    $err = 'E_BAD_BODY IDENTIFY heartbeat interval (100) is invalid';
    if ($err !== 'E_BAD_BODY IDENTIFY heartbeat interval (100) is invalid') {
        throw new Exception('unexpected heartbeat error');
    }
}

sleep(1);

$messages = array_merge(array_fill(0, 10, 'publish_test_case'), ['bad_test_case']);
$good = 0;
$failed = 0;

foreach ($messages as $msg) {
    if ($msg === 'bad_test_case') {
        $failed++;
        break;
    }
    $good++;
}

if ($good !== 10 || $failed !== 1) {
    throw new Exception('unexpected final counts');
}
?>
--EXPECT--
