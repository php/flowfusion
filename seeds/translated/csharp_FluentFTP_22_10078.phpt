--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/22)
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
    "PWD = ~TNA=AMP,VNA=VOL03,FNA=U-ED-B2-USL",
    "Verbundmörtel Zubehör + Technische Daten DE.pdf",
];

foreach ($sample as $s) {
    if (preg_match('/PWD = (?<pwd>.*)/', $s, $m)) echo $m['pwd'], PHP_EOL;
    elseif (preg_match('/\b(\d{2}-\d{2}-\d{2})\s+(\d{2}:\d{2}[AP]M)\s+(\d+)\s+(.+)$/', $s, $m)) echo $m[4], PHP_EOL;
    elseif (preg_match('/^\S+\s+\d+\s+\S+\s+\S+\s+\d+\s+\w+\s+\d+\s+\d{4}\s+(.+)$/', $s, $m)) echo $m[1], PHP_EOL;
    elseif (preg_match('/^(.+?)\s+->\s+(.+)$/', $s, $m)) echo $m[1], PHP_EOL;
    else echo $s, PHP_EOL;
}

$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "php_bug_" . uniqid();
mkdir($dir);
file_put_contents($dir . DIRECTORY_SEPARATOR . "a.txt", str_repeat("x", 8192));
file_put_contents($dir . DIRECTORY_SEPARATOR . "b.txt", str_repeat("y", 1));
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    $h = hash_file("sha256", $f->getPathname());
    if ($h === "") echo "";
}

echo "--DONE--", PHP_EOL;
?>
--EXPECT--
