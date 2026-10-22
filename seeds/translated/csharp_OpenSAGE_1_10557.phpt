--TEST--
Translated seed (inferredbugs-csharp: OpenSAGE/1)
--FILE--
<?php
$h = fopen('php://temp', 'r+');
fwrite($h, "CkMp");
rewind($h);
$f = fread($h, 4);
if ($f === "CkMp") {
    fseek($h, -4, SEEK_CUR);
}
rewind($h);
fwrite($h, "CkMp");
rewind($h);
$g = fread($h, 4);
if ($g !== "CkMp") {
    throw new Exception();
}
?>
--EXPECT--
