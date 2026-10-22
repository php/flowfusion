--TEST--
Translated seed (inferredbugs-csharp: FluentEmail/3)
--FILE--
<?php
$toEmail = "bob@test.com";
$fromEmail = "johno@test.com";
$subject = "sup dawg";
$body = "what be the hipitity hap?";

$boundary = "b" . bin2hex(random_bytes(8));
$attachment = "Hey this is some text in an attachment\r\n";

$message =
    "From: $fromEmail\r\n" .
    "To: $toEmail\r\n" .
    "Subject: $subject\r\n" .
    "MIME-Version: 1.0\r\n" .
    "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n" .
    "\r\n" .
    "--$boundary\r\n" .
    "Content-Type: text/plain; charset=UTF-8\r\n" .
    "Content-Transfer-Encoding: 7bit\r\n" .
    "\r\n" .
    $body . "\r\n" .
    "--$boundary\r\n" .
    "Content-Type: text/plain\r\n" .
    "Content-Disposition: attachment; filename=\"mailgunTest.txt\"\r\n" .
    "Content-Transfer-Encoding: 7bit\r\n" .
    "\r\n" .
    $attachment .
    "--$boundary--\r\n";

$fp = @fsockopen("localhost", 25, $errno, $errstr, 1.0);
if ($fp) {
    stream_set_timeout($fp, 1);
    fgets($fp);
    fwrite($fp, "HELO localhost\r\n");
    fgets($fp);
    fwrite($fp, "MAIL FROM:<$fromEmail>\r\n");
    fgets($fp);
    fwrite($fp, "RCPT TO:<$toEmail>\r\n");
    fgets($fp);
    fwrite($fp, "DATA\r\n");
    fgets($fp);
    fwrite($fp, $message . "\r\n.\r\n");
    fgets($fp);
    fwrite($fp, "QUIT\r\n");
    fclose($fp);
}
?>
--EXPECT--
