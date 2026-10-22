--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/19)
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
        echo $m['pwd'], "\n";
        continue;
    }

    if (preg_match('/^(?<date>\d{2}-\d{2}-\d{2})\s+(?<time>\d{2}:\d{2}[AP]M)\s+(?<size>\d+)\s+(?<name>.+)$/', $s, $m)) {
        echo $m['date'], " ", $m['time'], " ", $m['name'], "\n";
        continue;
    }

    if (preg_match('/^(?<name>[^;]+);\d+\s+(?<size>\d+)\s+(?<date>\d{2}-[A-Z]{3}-\d{4}\s+\d{2}:\d{2})\s+/', $s, $m)) {
        echo $m['date'], " ", $m['name'], "\n";
        continue;
    }

    echo $s, "\n";
}
echo "--DONE--\n";
?>
--EXPECT--
