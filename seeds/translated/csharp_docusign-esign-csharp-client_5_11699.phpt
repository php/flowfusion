--TEST--
Translated seed (inferredbugs-csharp: docusign-esign-csharp-client/5)
--FILE--
<?php
$input = str_repeat("a/b?c=d&e=f#x y", 3000);
echo rawurlencode($input), PHP_EOL;
echo base64_encode("DocuSign"), PHP_EOL;
echo implode(",", ["one", "two", "three"]), PHP_EOL;
echo str_replace(["\\", "/"], "", "C:\\temp\\folder/file.txt"), PHP_EOL;
$fp = fopen("php://temp", "r+");
fwrite($fp, str_repeat("0123456789", 2000));
rewind($fp);
$bytes = stream_get_contents($fp);
echo strlen($bytes), PHP_EOL;
echo json_encode(["scope" => ["a", "b"], "aud" => "x", "iss" => "y"]), PHP_EOL;
?>
--EXPECT--
