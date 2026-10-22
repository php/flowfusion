--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/16)
--FILE--
<?php
$sample = [
    "03-07-13  10:02AM                  901 File01.xml",
    "03-25-13  07:24AM                24537 File13.txt",
    "411_4114.TXT;1             11  21-MAR-2012 15:17 [TBMS,TBMS_BOSS] (RWED,RWED,,RE)",
    "DET_4114_124.TXT;1       4610  21-MAR-2012 15:18 [TBMS,TBMS_BOSS] (RWED,RWED,,RE)",
    "PWD = ~TNA=AMP,VNA=VOL03,FNA=U-ED-B2-USL",
];
foreach ($sample as $s) {
    if (preg_match('/PWD = (?<pwd>.*)/', $s, $m)) echo $m['pwd'] . PHP_EOL;
    elseif (preg_match('/^(\d{2}-\d{2}-\d{2})\s+(\d{2}:\d{2}[AP]M)\s+(\d+)\s+(.+)$/', $s, $m)) echo $m[1] . ' ' . $m[2] . ' ' . $m[3] . ' ' . $m[4] . PHP_EOL;
    elseif (preg_match('/^(.+?);(\d+)\s+(\d+)\s+(\d{2}-[A-Z]{3}-\d{4})\s+(\d{2}:\d{2})\s+\[(.+?)\]\s+\((.+)\)$/', $s, $m)) echo $m[1] . ' ' . $m[2] . ' ' . $m[3] . ' ' . $m[4] . ' ' . $m[5] . ' ' . $m[6] . ' ' . $m[7] . PHP_EOL;
}
echo "--DONE--\n";
?>
--EXPECT--
