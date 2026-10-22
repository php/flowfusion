--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/4)
--FILE--
<?php
$f = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($f, "x");
$p = new class($f) {
    public string $FilePath;
    public function __construct(string $filePath) { $this->FilePath = $filePath; }
    public function __toString(): string {
        return "Video FilePath : " . $this->FilePath . PHP_EOL .
            "Video Root : " . dirname($this->FilePath) . PHP_EOL .
            "Video Name: " . basename($this->FilePath) . PHP_EOL .
            "Video Extension : " . pathinfo($this->FilePath, PATHINFO_EXTENSION) . PHP_EOL .
            "Video duration : " . 0 . PHP_EOL .
            "Audio format : " . "" . PHP_EOL .
            "Video format : " . "" . PHP_EOL .
            "Aspect Ratio : " . "" . PHP_EOL .
            "Framerate : " . 0 . "fps" . PHP_EOL .
            "Resolution : " . 0 . "x" . 0 . PHP_EOL .
            "Size : " . 0 . " MB";
    }
};
echo $p, PHP_EOL;
unlink($f);
--EXPECT--
