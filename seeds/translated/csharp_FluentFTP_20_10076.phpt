--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/20)
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
    if (preg_match('/PWD = (?<pwd>.*)/', $s, $m)) {
        echo "PWD: " . $m['pwd'] . PHP_EOL;
        continue;
    }
    if (preg_match('/^(?<perm>[dl-])[rwx-]{9}\s+\d+\s+\S+\s+\S+\s+\d+\s+\w+\s+\d+\s+(?<year>\d{4})\s+(?<name>.+)$/', $s, $m)) {
        echo $m['name'] . PHP_EOL;
        continue;
    }
    if (preg_match('/^\d{2}-\d{2}-\d{2}\s+\d{2}:\d{2}[AP]M\s+\d+\s+(?<name>\S+)$/', $s, $m)) {
        echo $m['name'] . PHP_EOL;
        continue;
    }
    if (preg_match('/^(?<name>[^;]+);\d+\s+\d+\s+\d{2}-[A-Z]{3}-\d{4}\s+\d{2}:\d{2}\s+\[.*\]\s+\(.*\)$/', $s, $m)) {
        echo $m['name'] . PHP_EOL;
        continue;
    }
    echo $s . PHP_EOL;
}
echo "--DONE--" . PHP_EOL;
?>
--EXPECT--
