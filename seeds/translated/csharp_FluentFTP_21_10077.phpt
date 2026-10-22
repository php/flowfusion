--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/21)
--FILE--
<?php
$sample = [
    "drwxr-xr-x   7  user1 user1       512 Sep 27  2011 .",
    "drwxr-xr-x  31 user1  user1      1024 Sep 27  2011 ..",
    "lrwxrwxrwx   1 user1  user1      9 Sep 27  2011 data.0000 -> data.6460",
    "drwxr-xr-x  10 user1  user1      512 Jun 29  2012 data.6460",
    "lrwxrwxrwx   1 user1 user1       8 Sep 27  2011 sys.0000 -> sys.6460",
    "drwxr-xr-x 133 user1  user1     4096 Jun 25 16:26 sys.6460",
    "03-07-13  10:02AM                  901 File01.xml",
    "03-25-13  07:24AM                24537 File13.txt",
    "411_4114.TXT;1             11  21-MAR-2012 15:17 [TBMS,TBMS_BOSS] (RWED,RWED,,RE)",
    "TEL_4114.TXT;1           1178  21-MAR-2012 15:19 [TBMS,TBMS_BOSS] (RWED,RWED,,RE)",
    "PWD = ~TNA=AMP,VNA=VOL03,FNA=U-ED-B2-USL",
    "Verbundmörtel Zubehör + Technische Daten DE.pdf"
];

foreach ($sample as $s) {
    if (preg_match('/^PWD = (?<pwd>.*)$/', $s, $m)) {
        echo $m['pwd'], PHP_EOL;
        continue;
    }
    if (preg_match('/->\s+(\S+)$/', $s, $m)) {
        echo $m[1], PHP_EOL;
        continue;
    }
    if (preg_match('/\b([A-Za-z0-9_.+\x{00C0}-\x{FFFF} -]+\.(?:xml|txt|pdf))$/u', $s, $m)) {
        echo $m[1], PHP_EOL;
    }
}

$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "php_bug_" . uniqid();
mkdir($dir);
file_put_contents($dir . DIRECTORY_SEPARATOR . "Test.Directory", "x");
file_put_contents($dir . DIRECTORY_SEPARATOR . "Verbundmörtel Zubehör + Technische Daten DE.pdf", "y");
$h = opendir($dir);
while (($f = readdir($h)) !== false) {
    if ($f !== "." && $f !== "..") {
        $p = $dir . DIRECTORY_SEPARATOR . $f;
        clearstatcache(true, $p);
        $st = stat($p);
        $st['size'];
    }
}
closedir($h);
unlink($dir . DIRECTORY_SEPARATOR . "Test.Directory");
unlink($dir . DIRECTORY_SEPARATOR . "Verbundmörtel Zubehör + Technische Daten DE.pdf");
rmdir($dir);
echo "--DONE--", PHP_EOL;
?>
--EXPECT--
