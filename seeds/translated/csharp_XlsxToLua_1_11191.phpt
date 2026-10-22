--TEST--
Translated seed (inferredbugs-csharp: XlsxToLua/1)
--FILE--
<?php
$path = $argv[1] ?? __FILE__;
if (!is_file($path)) {
    echo $path . "文件不存在\n";
    exit;
}
if (!is_readable($path)) {
    echo $path . "文件正在被其他软件打开，请关闭后重新运行本工具\n";
    exit;
}

$fh = fopen($path, 'rb');
if ($fh === false) {
    echo "错误：连接Excel失败，你可能尚未安装Office数据连接组件: http://www.microsoft.com/en-US/download/details.aspx?id=23734 \n";
    exit;
}

$head = fread($fh, 16);
if ($head === false || strlen($head) < 4) {
    fclose($fh);
    echo "错误：连接Excel失败，你可能尚未安装Office数据连接组件: http://www.microsoft.com/en-US/download/details.aspx?id=23734 \n";
    exit;
}

if (substr($head, 0, 2) === "PK") {
    $zip = new ZipArchive();
    if ($zip->open($path) !== true) {
        fclose($fh);
        echo "错误：连接Excel失败，你可能尚未安装Office数据连接组件: http://www.microsoft.com/en-US/download/details.aspx?id=23734 \n";
        exit;
    }
    $hasData = false;
    $hasConfig = false;
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if ($name === 'xl/worksheets/sheet1.xml') $hasData = true;
        if ($name === 'xl/worksheets/sheet2.xml') $hasConfig = true;
    }
    $zip->close();
    if (!$hasData) {
        fclose($fh);
        echo "错误：{$path}中不含有Sheet名为data的数据表\n";
        exit;
    }
    if ($hasConfig) {
        $zip = new ZipArchive();
        if ($zip->open($path) === true) {
            $zip->close();
        }
    }
} else {
    rewind($fh);
    while (!feof($fh)) {
        fread($fh, 8192);
    }
}

fclose($fh);
echo "ok\n";
?>
--EXPECT--
