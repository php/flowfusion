--TEST--
Translated seed (inferredbugs-csharp: RazorEngineCore/4)
--FILE--
<?php
$file = tempnam(sys_get_temp_dir(), 'tpl_');
file_put_contents($file, "<?php return static fn(\$model = null) => \$model === null ? 'ok' : (is_array(\$model) ? json_encode(\$model) : get_debug_type(\$model));");

$code = file_get_contents($file);
$template = eval('return ' . $code . ';');

$model = ['a' => 1, 'b' => 2];
$result = $template($model);

$out = tempnam(sys_get_temp_dir(), 'out_');
file_put_contents($out, $result);
echo file_get_contents($out);

unlink($file);
unlink($out);
?>
--EXPECT--
