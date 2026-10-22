--TEST--
Translated seed (inferredbugs-csharp: alba/1)
--FILE--
<?php
declare(strict_types=1);

$body = "Hello, World!";
$headers = ["content-type" => "text/plain"];
$status = 200;

if ($status !== 200) {
    throw new RuntimeException("unexpected status");
}

if (($headers["content-type"] ?? "") !== "text/plain") {
    throw new RuntimeException("unexpected content type");
}

echo $body;
?>
--EXPECT--
