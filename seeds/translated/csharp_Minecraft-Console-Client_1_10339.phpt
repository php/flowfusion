--TEST--
Translated seed (inferredbugs-csharp: Minecraft-Console-Client/1)
--FILE--
<?php
$texts = [
    "§aAlice whispers to you: hello",
    "<*Faction Bob> hi",
    "[Channel] [Rank] User: Message",
    "From Carol: ping",
    "Alice has requested to teleport to you.",
    "§c<*Faction ~Dave> hello there",
    "[Prefix] [~Eve -> me] hey",
];
foreach ($texts as $t) {
    $v = preg_replace('/§./u', '', $t);
    $m = $s = '';
    if ($v !== '' && preg_match('/^([A-Za-z0-9_]+) whispers to you: (.*)$/', $v, $r)) {
        $s = $r[1]; $m = $r[2];
    } elseif ($v !== '' && preg_match('/^<[^>]*\s([A-Za-z0-9_~]+)> ?(.*)$/', $v, $r)) {
        $s = ltrim($r[1], '~'); $m = $r[2];
    } elseif ($v !== '' && preg_match('/^\[.*\] \[.*\] ([A-Za-z0-9_~]+): (.*)$/', $v, $r)) {
        $s = ltrim($r[1], '~'); $m = $r[2];
    } elseif ($v !== '' && preg_match('/^From ([A-Za-z0-9_]+): (.*)$/', $v, $r)) {
        $s = $r[1]; $m = $r[2];
    } elseif ($v !== '' && preg_match('/^([A-Za-z0-9_~]+) has requested to teleport to you\.$/', $v, $r)) {
        $s = ltrim($r[1], '~');
    }
    if ($s !== '') {
        $m . $s;
    }
}
?>
--EXPECT--
