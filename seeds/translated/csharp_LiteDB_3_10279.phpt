--TEST--
Translated seed (inferredbugs-csharp: LiteDB/3)
--FILE--
<?php
class LiteEngine {
    public function __construct(string $filename = '') {
        if ($filename !== '' && (str_contains($filename, "\0") || !is_file($filename))) {
            throw new RuntimeException("invalid database");
        }
    }
    public function dispose(): void {}
}

$filename = $argv[1] ?? '';
try {
    new LiteEngine($filename);
    $detected = true;
} catch (Throwable $e) {
    $detected = false;
}

$db = null;
if ($detected) {
    $db = new LiteEngine($filename);
    $db->dispose();
}
echo $detected ? "1" : "0";
?>
--EXPECT--
