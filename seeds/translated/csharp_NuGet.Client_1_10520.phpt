--TEST--
Translated seed (inferredbugs-csharp: NuGet.Client/1)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_cfg_' . bin2hex(random_bytes(4));
@mkdir($root, 0777, true);
$file = $root . DIRECTORY_SEPARATOR . 'NuGet.config';
file_put_contents($file, '<configuration><appSettings><add key="k" value="v1"/><clear/><add key="k" value="v2"/></appSettings><paths><add key="p" value="\\folder\\file"/></paths></configuration>');
$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->load($file);
$xp = new DOMXPath($doc);

$val = null;
foreach ($xp->query('/configuration/appSettings/*') as $e) {
    $n = strtolower($e->nodeName);
    if ($n === 'clear') {
        $val = null;
    } elseif ($n === 'add' && strcasecmp($e->getAttribute('key'), 'k') === 0) {
        $val = $e->getAttribute('value');
    }
}

$pathVal = null;
foreach ($xp->query('/configuration/paths/*') as $e) {
    if (strtolower($e->nodeName) === 'add' && strcasecmp($e->getAttribute('key'), 'p') === 0) {
        $pathVal = $e->getAttribute('value');
    }
}

if ($pathVal !== null && $pathVal !== '') {
    $dir = dirname(realpath($file));
    if (preg_match('/^[\\\\\\/][^\\\\\\/]/', $pathVal)) {
        $pathVal = dirname($dir) . substr($pathVal, 1);
    } elseif (!preg_match('/^(?:[A-Za-z]:)?[\\\\\\/]/', $pathVal)) {
        $pathVal = $dir . DIRECTORY_SEPARATOR . $pathVal;
    } else {
        $pathVal = $dir . DIRECTORY_SEPARATOR . ltrim($pathVal, "\\/");
    }
    $pathVal = $root . DIRECTORY_SEPARATOR . ltrim($pathVal, "\\/");
}

echo ($val ?? '') . PHP_EOL . ($pathVal ?? '') . PHP_EOL;
?>
--EXPECT--
