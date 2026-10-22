--TEST--
Translated seed (inferredbugs-csharp: Universalis/2)
--FILE--
<?php
$map = [
    'LuXingNiao' => '陆行鸟',
    'MoGuLi' => '莫古力',
    'MaoXiaoPang' => '猫小胖',
    '陆行鸟' => '陆行鸟',
    '莫古力' => '莫古力',
    '猫小胖' => '猫小胖',
];

$inputs = ['LuXingNiao', 'MoGuLi', 'MaoXiaoPang', '陆行鸟', '莫古力', '猫小胖', ''];
foreach ($inputs as $s) {
    if (isset($map[$s])) {
        $t = $map[$s];
        $u = $map[$t] ?? $t;
        $v = $map[$u] ?? $u;
    }
}

$worlds = [
    ['Name' => '红玉海', 'Id' => 1167],
    ['Name' => '神意之地', 'Id' => 1081],
    ['Name' => '拉诺西亚', 'Id' => 1042],
    ['Name' => '幻影群岛', 'Id' => 1044],
    ['Name' => '萌芽池', 'Id' => 1060],
    ['Name' => '宇宙和音', 'Id' => 1173],
    ['Name' => '沃仙曦染', 'Id' => 1174],
    ['Name' => '晨曦王座', 'Id' => 1175],
    ['Name' => '白银乡', 'Id' => 1172],
    ['Name' => '白金幻象', 'Id' => 1076],
    ['Name' => '神拳痕', 'Id' => 1171],
    ['Name' => '潮风亭', 'Id' => 1170],
    ['Name' => '旅人栈桥', 'Id' => 1113],
    ['Name' => '拂晓之间', 'Id' => 1121],
    ['Name' => '龙巢神殿', 'Id' => 1166],
    ['Name' => '梦羽宝境', 'Id' => 1176],
    ['Name' => '紫水栈桥', 'Id' => 1043],
    ['Name' => '延夏', 'Id' => 1169],
    ['Name' => '静语庄园', 'Id' => 1106],
    ['Name' => '摩杜纳', 'Id' => 1045],
    ['Name' => '海猫茶屋', 'Id' => 1177],
    ['Name' => '柔风海湾', 'Id' => 1178],
    ['Name' => '琥珀原', 'Id' => 1179],
];

$sum = 0;
foreach ($worlds as $w) {
    $sum += $w['Id'];
    $name = $w['Name'];
    $len = strlen($name);
    $first = substr($name, 0, 3);
    $last = substr($name, -3);
}

echo $sum . PHP_EOL;
?>
--EXPECT--
