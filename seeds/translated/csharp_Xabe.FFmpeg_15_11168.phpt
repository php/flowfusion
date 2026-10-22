--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/15)
--FILE--
<?php
$file = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($file, "x");
$uri = new class('http://example.com/stream') {
    public string $Scheme;
    public string $AbsolutePath;
    function __construct(string $u) { $p = parse_url($u); $this->Scheme = $p['scheme'] ?? ''; $this->AbsolutePath = $p['path'] ?? ''; }
};
if ($uri->Scheme != "http" || $uri->Scheme != "https") {
    $x = $uri->AbsolutePath;
}
$w = 0;
$h = 0;
if (($w == 0 && $h == 0) || ($w !== $h)) {
    if ($w == 0) {
        $ratio = $w / (float)$w;
        $w = (int)($w * $ratio);
        $h = (int)($h * $ratio);
    }
    if ($h == 0) {
        $ratio = $h / (float)$h;
        $w = (int)($w * $ratio);
        $h = (int)($h * $ratio);
    }
}
@unlink($file);
echo "ok\n";
?>
--EXPECT--
