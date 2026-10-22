--TEST--
Translated seed (inferredbugs-csharp: ZKEACMS/1)
--FILE--
<?php
$widgets = [
    ['ID' => 'w1', 'ZoneID' => 'A', 'Position' => 1, 'StyleClass' => 'foo bar', 'CustomClass' => 'base', 'PageID' => 'p1', 'LayoutID' => ''],
    ['ID' => 'w2', 'ZoneID' => 'A', 'Position' => 2, 'StyleClass' => '', 'CustomClass' => 'base', 'PageID' => '', 'LayoutID' => 'l1'],
];

$cookie = [];
$copyKey = 'copy_widget';

$cookie[$copyKey] = 'w1';
$copiedId = $cookie[$copyKey];

foreach ($widgets as &$w) {
    if ($w['ID'] === $copiedId) {
        $w['ZoneID'] = 'B';
        $w['Position'] = 99;
        if (strpos($w['StyleClass'], 'foo') !== false) {
            $w['StyleClass'] = trim(str_replace('foo', '', $w['StyleClass']));
        } else {
            $w['StyleClass'] = trim('foo ' . $w['StyleClass']);
        }
        if (trim('') !== '') {
            $w['StyleClass'] = trim($w['CustomClass'] . ' style="' . '' . '"');
        } else {
            $w['StyleClass'] = $w['CustomClass'];
        }
    }
}
unset($w);

$package = [
    'Widget' => ['WidgetName' => 'demo'],
    'Content' => json_encode(['Widget' => ['WidgetName' => 'demo']]),
];

$json = json_encode($package);
$decoded = json_decode($json, true);
if (is_array($decoded) && isset($decoded['Widget']['WidgetName'])) {
    $fileName = $decoded['Widget']['WidgetName'] . '.widget';
    $bytes = $json;
}

echo "ok\n";
?>
--EXPECT--
