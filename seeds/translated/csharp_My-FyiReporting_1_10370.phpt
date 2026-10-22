--TEST--
Translated seed (inferredbugs-csharp: My-FyiReporting/1)
--FILE--
<?php
$source = "a.rdl";
$files = [
    "report.pdf",
    "report.xml",
    "report.html",
    "report.htm",
    "report.csv",
    "report.rtf",
    "report.mht",
    "report.mhtml",
    "report.xlsx",
    "report.tif",
    "report.tiff",
    "report",
    ".hidden",
    "report.PDF",
    "report.tar.gz",
];

foreach ($files as $file) {
    $i = strrpos($file, '.');
    $ext = $i < 1 ? "" : strtolower(substr($file, $i + 1));
    switch ($ext) {
        case "pdf":
        case "xml":
        case "html":
        case "htm":
        case "csv":
        case "rtf":
        case "mht":
        case "mhtml":
        case "xlsx":
        case "tif":
        case "tiff":
            $type = $ext;
            break;
        default:
            $type = "internal";
            break;
    }
    $initial = $source !== null ? (substr($source, 0, strrpos($source, '.')) . ".pdf") : "*.pdf";
    $result = [$file, $ext, $type, $initial];
}
echo "ok\n";
?>
--EXPECT--
