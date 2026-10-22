--TEST--
Translated seed (inferredbugs-csharp: FluentEmail/2)
--FILE--
<?php
$to = [];
$from = null;
$subject = '';
$body = '';
$error = null;
$hasError = false;

$to[] = ['email' => 'user@example.com', 'name' => ''];
$from = ['email' => 'sender@example.com', 'name' => ''];
$subject = "test";
$body = "<b>html</b>";

try {
    if (!filter_var($from['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("invalid from");
    }
    foreach ($to as $addr) {
        if (!filter_var($addr['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("invalid to");
        }
    }
    if ($body === '') {
        throw new Exception("empty body");
    }
    $message = [
        'isBodyHtml' => true,
        'to' => $to,
        'from' => $from,
        'subject' => $subject,
        'body' => $body,
    ];
    $serialized = serialize($message);
    $unserialized = unserialize($serialized);
    $copy = $unserialized;
    $copy['subject'] = $copy['subject'] . '';
} catch (Throwable $ex) {
    $error = $ex;
    $hasError = true;
}

echo $hasError ? "error" : "ok";
?>
--EXPECT--
