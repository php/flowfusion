--TEST--
Translated seed (inferredbugs-csharp: RazorEngineCore/3)
--FILE--
<?php
$src = tempnam(sys_get_temp_dir(), 'tpl_');
$dst = tempnam(sys_get_temp_dir(), 'out_');
file_put_contents($src, "<?php return ['TemplateNamespace\\\\Template' => 'ok'];");

$bytes = file_get_contents($src);
$tmp = fopen('php://memory', 'r+');
fwrite($tmp, $bytes);
rewind($tmp);
$copy = stream_get_contents($tmp);
$data = include $src;
$result = $data['TemplateNamespace\\\\Template'] ?? null;
file_put_contents($dst, $copy);

$model = (object)['x' => 1];
if ($model !== null && is_object($model) && get_class($model) === 'stdClass') {
    $model = ['wrapped' => $model];
}

echo $result;
unlink($src);
unlink($dst);
?>
--EXPECT--
