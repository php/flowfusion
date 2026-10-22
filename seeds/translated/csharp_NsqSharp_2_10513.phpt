--TEST--
Translated seed (inferredbugs-csharp: NsqSharp/2)
--FILE--
<?php
$topic = 'topic' . time();
$msgCount = 10;
$queue = [];

for ($i = 0; $i < $msgCount; $i++) {
    $queue[] = ['topic' => $topic, 'body' => 'publish_test_case', 'tag' => 'test'];
}

for ($i = 0; $i < $msgCount; $i++) {
    $tx = array_shift($queue);
    if ($tx['topic'] !== $topic || $tx['body'] !== 'publish_test_case' || $tx['tag'] !== 'test') {
        throw new Exception('bad transaction');
    }
}

$queue[] = ['topic' => $topic, 'body' => 'bad_test_case', 'tag' => null];

$multi = [];
for ($i = 0; $i < $msgCount; $i++) {
    $multi[] = 'multipublish_test_case';
}

foreach ($multi as $body) {
    $queue[] = ['topic' => $topic, 'body' => $body, 'tag' => null];
}

$async = [];
for ($i = 0; $i < $msgCount; $i++) {
    $async[] = ['topic' => $topic, 'body' => 'publish_test_case', 'tag' => 'test'];
}

foreach ($async as $tx) {
    if ($tx['tag'] !== 'test' || $tx['body'] !== 'publish_test_case') {
        throw new Exception('bad async transaction');
    }
}

$queue[] = ['topic' => $topic, 'body' => 'bad_test_case', 'tag' => null];

$good = 0;
$failed = 0;

while ($queue) {
    $message = array_shift($queue);
    $msg = $message['body'];
    if ($msg === 'bad_test_case') {
        $failed++;
        continue;
    }
    if ($msg !== 'multipublish_test_case' && $msg !== 'publish_test_case') {
        throw new Exception('message action was not correct ' . $msg);
    }
    $good++;
}

if ($good !== $msgCount * 2 || $failed !== 2) {
    throw new Exception('unexpected counts');
}
?>
--EXPECT--
