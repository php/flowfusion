--TEST--
Translated seed (inferredbugs-csharp: Simple.Web/1)
--FILE--
<?php
$template = '<!DOCTYPE html><html><body>@Model.Text</body></html>';
$model = (object)['Text' => 'Test Text'];
$out = preg_replace_callback('/@Model\.Text/', function ($m) use ($model) { return htmlspecialchars($model->Text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }, $template);
echo $out;
?>
--EXPECT--
