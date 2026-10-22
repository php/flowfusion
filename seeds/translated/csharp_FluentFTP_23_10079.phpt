--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/23)
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
];

foreach ($sample as $s) {
    if (preg_match('/PWD = (?<pwd>.*)/', $s, $m)) {
        $x = $m['pwd'];
        continue;
    }
    if (preg_match('/^(?<perm>[dl-][rwx-]{9})\s+/', $s, $m)) {
        $x = $m['perm'];
        continue;
    }
    if (preg_match('/^\d{2}-\d{2}-\d{2}\s+\d{2}:\d{2}[AP]M\s+\d+\s+(?<name>.+)$/', $s, $m)) {
        $x = $m['name'];
        continue;
    }
    if (preg_match('/^(?<name>[^;]+);\d+\s+\d+\s+\d{2}-[A-Z]{3}-\d{4}\s+\d{2}:\d{2}/', $s, $m)) {
        $x = $m['name'];
        continue;
    }
}

$filename = "Verbundmörtel Zubehör + Technische Daten DE.pdf";
$tmp = tempnam(sys_get_temp_dir(), 'php');
file_put_contents($tmp, $filename . PHP_EOL);
file_get_contents($tmp);
unlink($tmp);
echo "--DONE--\n";
?>
--EXPECT--
