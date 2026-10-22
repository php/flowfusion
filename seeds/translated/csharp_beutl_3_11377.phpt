--TEST--
Translated seed (inferredbugs-csharp: beutl/3)
--FILE--
<?php
final class TabViewModel {
    public string $FilePath;
    public string $Extension;
    public bool $IsSelected = false;
    public function __construct(string $path, string $extension) {
        $this->FilePath = $path;
        $this->Extension = $extension;
    }
}

$tabItems = [];
$recent = [];

$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bug_pattern_' . uniqid('', true);
@mkdir($dir);
$f1 = $dir . DIRECTORY_SEPARATOR . 'a.txt';
$f2 = $dir . DIRECTORY_SEPARATOR . 'b.txt';
file_put_contents($f1, 'x');
file_put_contents($f2, 'y');

$selectOrAdd = function (?string $file) use (&$tabItems, &$recent) {
    if ($file !== null && file_exists($file)) {
        $recent[] = $file;
        foreach ($tabItems as $tab) {
            if ($tab->FilePath === $file) {
                $tab->IsSelected = true;
                return;
            }
        }
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext !== '') {
            $tabItems[] = (function ($file, $ext) {
                $t = new TabViewModel($file, $ext);
                $t->IsSelected = true;
                return $t;
            })($file, $ext);
        }
    }
};

$closeTab = function (?string $file) use (&$tabItems) {
    if ($file === null) return;
    foreach ($tabItems as $i => $tab) {
        if ($tab->FilePath === $file) {
            array_splice($tabItems, $i, 1);
            return;
        }
    }
};

$projectChanged = function (?array $new, ?array $old) use (&$selectOrAdd, &$closeTab) {
    if ($new !== null) {
        foreach ($new as $item) $selectOrAdd($item);
    }
    if ($old !== null) {
        foreach ($old as $item) $closeTab($item);
    }
};

$projectChanged([$f1, $f2], null);
$selectOrAdd($f1);
$selectOrAdd($f2);
$selectOrAdd($f1);
$closeTab($f2);
$projectChanged(null, [$f1]);

@unlink($f1);
@unlink($f2);
@rmdir($dir);
?>
--EXPECT--
