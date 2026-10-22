--TEST--
Translated seed (inferredbugs-csharp: LibOrbisPkg/2)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bugpattern_' . bin2hex(random_bytes(4));
mkdir($dir);
$file = $dir . DIRECTORY_SEPARATOR . 'a.b.c.pkg';
file_put_contents($file, str_repeat("PKG", 3) . "\0" . str_repeat("x", 7));
$base = basename($file);
$ext = pathinfo($file, PATHINFO_EXTENSION);
$parts = explode('.', $base);
$selected = $parts[count($parts) - 1];
$tabPages = [];
$tabPages[$selected] = ['name' => $base, 'content' => file_get_contents($file)];
$selectedTab = $selected;
unset($tabPages[$selectedTab]);
$selectedTab = array_key_last($tabPages);
if ($selectedTab !== null) {
    $name = $tabPages[$selectedTab]['name'];
    $len = strlen($tabPages[$selectedTab]['content']);
    $check = substr($name, -strlen($ext));
    if ($len < 0 || $check === null) {
        echo "";
    }
}
unlink($file);
rmdir($dir);
?>
--EXPECT--
