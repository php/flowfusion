--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/24)
--FILE--
<?php
$items = [
    "drwxr-xr-x   7  user1 user1       512 Sep 27  2011 .",
    "drwxr-xr-x  31 user1  user1      1024 Sep 27  2011 ..",
    "lrwxrwxrwx   1 user1  user1      9 Sep 27  2011 data.0000 -> data.6460",
    "drwxr-xr-x  10 user1  user1      512 Jun 29  2012 data.6460",
    "03-07-13  10:02AM                  901 File01.xml",
    "03-25-13  07:24AM                24537 File13.txt",
    "411_4114.TXT;1             11  21-MAR-2012 15:17 [TBMS,TBMS_BOSS] (RWED,RWED,,RE)",
    "PWD = ~TNA=AMP,VNA=VOL03,FNA=U-ED-B2-USL",
];
foreach ($items as $s) {
    if (preg_match('/^PWD = (?<pwd>.*)$/', $s, $m)) echo $m['pwd'], "\n";
    elseif (preg_match('/^(?<date>\d{2}-\d{2}-\d{2})\s+(?<time>\d{2}:\d{2}[AP]M)\s+(?<size>\d+)\s+(?<name>.+)$/', $s, $m)) echo $m['date'], " ", $m['time'], " ", $m['name'], "\n";
    elseif (preg_match('/^(?<name>.+?)\s*->\s*(?<target>.+)$/', $s, $m)) echo $m['name'], " => ", $m['target'], "\n";
    else echo $s, "\n";
}
$path = "/home/sigurdhj/errors/16.05.2014/asdasd/asd asd asd aa asd/Kooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo::asdasd";
echo dirname($path), "\n";
echo basename("./foobar"), "\n";
echo basename("foobar"), "\n";
echo basename($path), "\n";
echo basename("/foo/bar"), "\n";
echo basename("./foo/bar"), "\n";
echo basename("./bar"), "\n";
echo basename("/bar"), "\n";
echo basename("bar"), "\n";
$buf = str_repeat("abc/..//./", 1000);
echo substr_count($buf, "/"), "\n";
echo "--DONE--\n";
?>
--EXPECT--
