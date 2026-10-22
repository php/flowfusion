--TEST--
Translated seed (inferredbugs-csharp: luban/2)
--FILE--
<?php
$records = [
    ["a" => 1, "b" => [1, 2], "s" => "x\u{2028}y", "n" => null],
    ["a" => -1, "b" => [], "s" => "\"\\/\b\f\n\r\t", "n" => true],
];
$table = ["mode" => "ONE", "assembly" => null];

foreach (["data_bin", "data_json", "data_json2", "data_lua", "bad"] as $dataType) {
    try {
        switch ($dataType) {
            case "data_bin":
                $out = serialize($records);
                break;
            case "data_json":
            case "data_json2":
                $out = json_encode($records, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
                break;
            case "data_lua":
                $content = [];
                foreach ($records as $r) {
                    $content[] = "{" . implode(",", array_map(fn($v) => var_export($v, true), $r)) . "}";
                }
                $out = implode("\n", $content);
                break;
            default:
                throw new InvalidArgumentException("not support datatype:$dataType");
        }
    } catch (Throwable $e) {
        $out = $e->getMessage();
    }
    if ($out === '') {
        echo "";
    }
}

$sorted = ["z" => "last", "a" => "first", "m" => "mid"];
ksort($sorted);
$sb = '';
foreach ($sorted as $k => $v) {
    $sb .= $k . '|' . $v . "\n";
}
echo $sb;
?>
--EXPECT--
