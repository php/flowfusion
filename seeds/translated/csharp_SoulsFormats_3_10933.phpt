--TEST--
Translated seed (inferredbugs-csharp: SoulsFormats/3)
--FILE--
<?php
$bhd = "BHF4";
$bdt = "BDF4";
$bytes = $bhd . str_repeat("\0", 60);
$bytes2 = $bdt . str_repeat("\0", 60);

$magic1 = substr($bytes, 0, 4);
$magic2 = substr($bytes2, 0, 4);

if ($magic1 === "BHF4" && $magic2 === "BDF4") {
    $files = [];
    $files[] = ["id" => 1, "name" => "a/b/c", "flags" => 0x03, "bytes" => "hello"];
    $files[] = ["id" => 2, "name" => "x", "flags" => 0x00, "bytes" => str_repeat("z", 16)];

    $offset = 0;
    $packed = "";
    foreach ($files as $i => $file) {
        $offset = ($offset + 0x0f) & ~0x0f;
        $data = $file["bytes"];
        if ($file["flags"] === 0x03 || $file["flags"] === 0xC0) {
            $data = gzcompress($data);
        }
        $packed .= str_repeat("\0", $offset - strlen($packed)) . $data;
        $offset += strlen($data);
    }

    $hashGroups = [];
    foreach ($files as $i => $file) {
        $h = crc32($file["name"]);
        $g = $h % 3;
        $hashGroups[$g][] = [$h, $i];
    }
    foreach ($hashGroups as &$group) {
        usort($group, fn($a, $b) => $a[0] <=> $b[0]);
    }
    unset($group);
}

echo "";
?>
--EXPECT--
