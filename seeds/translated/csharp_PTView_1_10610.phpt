--TEST--
Translated seed (inferredbugs-csharp: PTView/1)
--FILE--
<?php
$hex = '0xFFFF_FFFF_FFFF_FFFF';
$s = str_replace('0x', '', $hex);
if (preg_match('/^[0-9A-Fa-f_]+$/', $s)) {
    $v = 0;
    foreach (str_split(str_replace('_', '', $s)) as $ch) {
        $v = ($v << 4) | hexdec($ch);
    }
    $pml4 = ($v >> 39) & 0x1ff;
    $pdpt = ($v >> 30) & 0x1ff;
    $pd = ($v >> 21) & 0x1ff;
    $pt = ($v >> 12) & 0x1ff;
    $canonical = $pml4 > 255 ? (($v & ((1 << 48) - 1)) | (((1 << 16) - 1) << 48)) : ($v & ((1 << 48) - 1));
    echo "PML4 Index: $pml4\nPDPT Index: $pdpt\nPD Index: $pd\nPT Index: $pt\n";
    echo sprintf("0x%016X\n", $canonical);
}
?>
--EXPECT--
