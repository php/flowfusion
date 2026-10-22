--TEST--
Translated seed (inferredbugs-csharp: Templify/1)
--FILE--
<?php
class Manifest {
    public string $Path = '';
}

class Package {
    public ?Manifest $Manifest = null;
}

$path = $argv[1] ?? '';
$package = new Package();

try {
    $zip = new ZipArchive();
    if ($zip->open($path) === true) {
        $xml = $zip->getFromName('manifest.xml');
        if ($xml !== false) {
            $manifest = new Manifest();
            if (preg_match('/<Path>(.*?)<\/Path>/s', $xml, $m)) {
                $manifest->Path = $m[1];
            }
            $manifest->Path = $path;
            $package->Manifest = $manifest;
        }
        $zip->close();
    }
} catch (Throwable $e) {
}

echo "";
?>
--EXPECT--
