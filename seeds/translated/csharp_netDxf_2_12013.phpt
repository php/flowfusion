--TEST--
Translated seed (inferredbugs-csharp: netDxf/2)
--FILE--
<?php
$xs = ["\\U+0041", "\\U+20AC", "abc\\U+0041def", "\\U+0041\\U+0042", "plain", "", "\\U+0041x\\U+0042y\\U+0043"];
$cache = [];
for ($i = 0; $i < 5000; $i++) {
    foreach ($xs as $s) {
        if ($s === "") continue;
        if (isset($cache[$s])) {
            $d = $cache[$s];
        } else {
            $len = strlen($s);
            $d = "";
            for ($j = 0; $j < $len; $j++) {
                $c = $s[$j];
                if ($c === "\\" && $j + 6 < $len && ($s[$j + 1] === "U" || $s[$j + 1] === "u") && $s[$j + 2] === "+") {
                    $hex = substr($s, $j + 3, 4);
                    if (ctype_xdigit($hex)) {
                        $d .= html_entity_decode("&#" . hexdec($hex) . ";", ENT_NOQUOTES, "UTF-8");
                        $j += 6;
                        continue;
                    }
                }
                $d .= $c;
            }
            $cache[$s] = $d;
        }
        $r = str_replace(["^I", "^J"], ["\t", "\\P"], $d);
        $u = strtoupper($r);
        $v = strtolower($u);
        $w = substr($v, 0, 1) . substr($v, 1);
        if ($w === "\0") echo "";
    }
}
echo "ok\n";
?>
--EXPECT--
