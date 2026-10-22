--TEST--
Translated seed (inferredbugs-csharp: swan/1)
--FILE--
<?php
final class Settings {
    public int $Port = 9696;
    public string $User = "User";
}

$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "appsettings.json";
@unlink($path);

if (!is_file($path) || filesize($path) === 0) {
    $g = new Settings();
    file_put_contents($path, json_encode($g, JSON_PRETTY_PRINT));
} else {
    $g = json_decode(file_get_contents($path));
}

if ($g === null) {
    $g = new Settings();
}

$g->Port = 20;
file_put_contents($path, json_encode($g, JSON_PRETTY_PRINT));

$g2 = json_decode(file_get_contents($path));
if ($g2 === null) {
    $g2 = new Settings();
}
echo $g2->User . ":" . $g2->Port . PHP_EOL;
?>
--EXPECT--
