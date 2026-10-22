--TEST--
Translated seed (inferredbugs-csharp: SoulsFormats/2)
--FILE--
<?php
$bhd = "BHF4" . str_repeat("\0", 64);
$bdt = "BDF4" . str_repeat("\0", 64);
$bhd[8] = "\1";
$bhd[9] = "\0";
$bhd[16] = "\x01";
$bhd[20] = "\1";
$bhd[24] = "\x74";
$bhd[25] = "\4";
$bhd[32] = "\x30";
$bhd[40] = "\0";
$bhd[41] = "\0";
$bhd[42] = "\0";
$bhd[43] = "\0";
$bdt[8] = "\1";
$bdt[9] = "\0";
$bdt[16] = "\x01";
$bdt[24] = "\x30";
$bhd2 = substr($bhd, 0, 4);
$bdt2 = substr($bdt, 0, 4);
if ($bhd2 === "BHF4" && $bdt2 === "BDF4") {
    echo "";
}
?>
--EXPECT--
