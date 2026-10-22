--TEST--
Translated seed (inferredbugs-csharp: slskd/4)
--FILE--
<?php
$base = __DIR__;
$app = "slskd";
$cfg = $base . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . $app . ".yml";
$dir = $base . DIRECTORY_SEPARATOR . "tmp_" . bin2hex(random_bytes(4));
$sub = $dir . DIRECTORY_SEPARATOR . "shared";
@mkdir($sub, 0777, true);
$probe = $sub . DIRECTORY_SEPARATOR . bin2hex(random_bytes(8));
file_put_contents($probe, "");
unlink($probe);
echo $app . PHP_EOL;
echo $cfg . PHP_EOL;
echo $dir . PHP_EOL;
echo $sub . PHP_EOL;
--EXPECT--
