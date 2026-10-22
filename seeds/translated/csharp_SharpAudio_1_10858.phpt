--TEST--
Translated seed (inferredbugs-csharp: SharpAudio/1)
--FILE--
<?php
$s = str_repeat("A", 44100);
$a = [$s];
$b = [$a];
$c = [$b];
$mix = array_merge($c, $c);
$mix[0][0][0] = substr($mix[0][0][0], 0, 22050) . substr($mix[0][0][0], 22050);
$vol = 0.5;
$out = "";
foreach ($mix as $x) {
    foreach ($x as $y) {
        foreach ($y as $z) {
            $out .= $z;
        }
    }
}
$out = substr($out, 0, (int)(strlen($out) * $vol)) . substr($out, (int)(strlen($out) * $vol));
echo strlen($out), "\n";
?>
--EXPECT--
