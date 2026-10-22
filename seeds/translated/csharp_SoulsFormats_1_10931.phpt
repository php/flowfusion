--TEST--
Translated seed (inferredbugs-csharp: SoulsFormats/1)
--FILE--
<?php
abstract class SoulsFile {
    public $Compression = 0;
    abstract protected function isData(string $data): bool;
    abstract protected function readData(string $data): void;
    abstract protected function writeData(): string;
    public static function isBytes(string $bytes): bool {
        return $bytes !== '';
    }
    public static function readBytes(string $bytes): self {
        $f = new static();
        $f->Compression = 0;
        $f->readData($bytes);
        return $f;
    }
    public function writeBytes(int $compression = null): string {
        $compression ??= $this->Compression;
        $data = $this->writeData();
        return $compression === 0 ? $data : gzencode($data);
    }
}

final class DemoFile extends SoulsFile {
    public $value = '';
    protected function isData(string $data): bool {
        return strlen($data) > 0 && $data[0] === 'X';
    }
    protected function readData(string $data): void {
        $this->value = $data;
    }
    protected function writeData(): string {
        return $this->value;
    }
}

$bytes = "X" . str_repeat("A", 1024);
$f = DemoFile::readBytes($bytes);
$f->Compression = 0;
$out = $f->writeBytes();
echo strlen($out), "\n";
?>
--EXPECT--
