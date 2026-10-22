--TEST--
Translated seed (inferredbugs-csharp: Nustache/2)
--FILE--
<?php
$viewPath = tempnam(sys_get_temp_dir(), 'view_');
$masterPath = tempnam(sys_get_temp_dir(), 'master_');

file_put_contents($viewPath, "Hello {{name}}");
file_put_contents($masterPath, "Start {{>Body}} End");

$viewTemplate = file_get_contents($viewPath);
$masterTemplate = file_get_contents($masterPath);

$data = ['name' => 'World'];
$body = preg_replace('/{{\s*name\s*}}/', $data['name'], $viewTemplate);
$output = preg_replace('/{{\s*>\s*Body\s*}}/', $body, $masterTemplate);

echo $output;

unlink($viewPath);
unlink($masterPath);
?>
--EXPECT--
