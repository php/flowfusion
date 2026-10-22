--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/25)
--FILE--
<?php
$path = "/home/sigurdhj/errors/16.05.2014/asdasd/asd asd asd aa asd/Kooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo::asdasd";
$tests = [
    $path,
    "./foobar",
    "foobar",
    "/foo/bar",
    "./foo/bar",
    "./bar",
    "/bar",
    "bar",
    "Verbundmörtel Zubehör + Technische Daten DE.pdf",
    "Caffè.jpg",
    "/public_html/temp/spaced folder/",
    "drwxr-xr-x   7  user1 user1       512 Sep 27  2011 .",
    "lrwxrwxrwx   1 user1  user1      9 Sep 27  2011 data.0000 -> data.6460",
    "03-07-13  10:02AM                  901 File01.xml",
    "411_4114.TXT;1             11  21-MAR-2012 15:17 [TBMS,TBMS_BOSS] (RWED,RWED,,RE)",
    "PWD = ~TNA=AMP,VNA=VOL03,FNA=U-ED-B2-USL",
];
foreach ($tests as $s) {
    $dir = preg_replace('~[\\\\/]+[^\\\\/]*$~', '', $s);
    $file = preg_replace('~^.*[\\\\/]~', '', rtrim($s, "/\\"));
    echo $dir, PHP_EOL, $file, PHP_EOL;
}
$sample = "a\0b\0c";
for ($i = 0; $i < strlen($sample); $i++) {
    $x = ord($sample[$i]);
    $y = ($x << 1) ^ ($x >> 1);
}
$tmp = tempnam(sys_get_temp_dir(), 'php');
file_put_contents($tmp, str_repeat("Caffè\n", 3));
$h = hash_file('sha256', $tmp);
echo $h, PHP_EOL;
unlink($tmp);
echo "--DONE--", PHP_EOL;
?>
--EXPECT--
