--TEST--
Translated seed (inferredbugs-csharp: NsqSharp/1)
--FILE--
<?php
$topic = 'publish' . time();
$msgCount = 10;
$queue = [];
for ($i = 0; $i < $msgCount; $i++) {
    $queue[] = "publish_test_case";
}
$queue[] = "bad_test_case";
foreach ($queue as $msg) {
    if ($msg === "bad_test_case") {
        continue;
    }
    if ($msg !== "publish_test_case") {
        throw new Exception("message 'action' was not correct $msg");
    }
}
$batch = [];
for ($i = 0; $i < $msgCount; $i++) {
    $batch[] = "multipublish_test_case";
}
foreach ($batch as $msg) {
    if ($msg !== "multipublish_test_case" && $msg !== "public_test_case") {
        throw new Exception("message 'action' was not correct $msg");
    }
}
$async = [];
for ($i = 0; $i < $msgCount; $i++) {
    $async[] = ["Error" => null, "Args" => ["test"]];
}
foreach ($async as $trans) {
    if ($trans["Error"] !== null) {
        throw new Exception("unexpected error");
    }
    if (!isset($trans["Args"]) || count($trans["Args"]) !== 1 || $trans["Args"][0] !== "test") {
        throw new Exception("unexpected args");
    }
}
echo "";
?>
--EXPECT--
