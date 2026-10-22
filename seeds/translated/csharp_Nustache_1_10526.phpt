--TEST--
Translated seed (inferredbugs-csharp: Nustache/1)
--FILE--
<?php
$viewPath = $argv[1] ?? __FILE__;
$templatePath = realpath($viewPath) ?: $viewPath;
$templateSource = file_get_contents($templatePath);
if ($templateSource === false) $templateSource = '';
echo $templateSource;
?>
--EXPECT--
