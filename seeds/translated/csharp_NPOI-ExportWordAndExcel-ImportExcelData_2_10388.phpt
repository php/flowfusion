--TEST--
Translated seed (inferredbugs-csharp: NPOI-ExportWordAndExcel-ImportExcelData/2)
--FILE--
<?php
$dir = sys_get_temp_dir() . '/php_excel_bug_' . date('Ymd');
if (!is_dir($dir)) mkdir($dir, 0777, true);
$path = $dir . '/人才培训课程表_' . date('YmdHis') . '.csv';

$fp = fopen($path, 'wb');
if ($fp === false) exit(1);

fwrite($fp, "\xEF\xBB\xBF");
fputcsv($fp, ['课程类型', '序号', '日期', '课程名称', '内容概要', '讲师简介']);
$today = new DateTimeImmutable();
$list = ['艺术学', '设计学', '材料学', '美学', '心理学', '中国近代史', '管理人员的情绪修炼', '高效时间管理', '有效的目标管理', '沟通与协调'];
for ($i = 0; $i < 10; $i++) {
    $n = $i + 1;
    fputcsv($fp, [
        $n === 1 ? '公共类课程' : ($n === 7 ? '管理类课程' : ''),
        (string)$n,
        $today->modify("+$n day")->format('Y-m-d'),
        $list[$i],
        '提升，充实，拓展自己综合实力',
        '追逐时光_' . $n . '号金牌讲师！'
    ]);
}
fclose($fp);

$in = fopen($path, 'rb');
if ($in !== false) {
    while (!feof($in)) fread($in, 8192);
    fclose($in);
}
unlink($path);
?>
--EXPECT--
