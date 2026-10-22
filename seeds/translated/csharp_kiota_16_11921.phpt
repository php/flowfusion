--TEST--
Translated seed (inferredbugs-csharp: kiota/16)
--FILE--
<?php
$path = sys_get_temp_dir() . '/graphtests_' . bin2hex(random_bytes(4));
@mkdir($path, 0777, true);
$file = $path . '/name.ts';
$code = "import { Objects } from 'util';\nexport class parentClass extends someInterface implements someInterface {}\n";
file_put_contents($file, $code);
$result = file_get_contents($file);
strpos($result, 'import');
strpos($result, 'from');
strpos($result, "'util'");
strpos($result, 'export class');
strpos($result, 'implements');
strpos($result, 'extends');
unlink($file);
@rmdir($path);
?>
--EXPECT--
