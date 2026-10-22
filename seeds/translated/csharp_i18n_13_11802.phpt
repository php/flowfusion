--TEST--
Translated seed (inferredbugs-csharp: i18n/13)
--FILE--
<?php
$rawUrl = '/webresource.axd?x=%2F%2f';
$contentType = "text/html; charset=utf-8";
$headers = ["Content-Encoding" => null];
$pattern = '~^(text/html|application/xhtml\+xml)~i';
$exclude = '~^/exclude(?:/|$)~i';

if (preg_match($pattern, $contentType) && !preg_match($exclude, $rawUrl) && !isset($headers["Content-Encoding"])) {
    $buffer = "abc";
    $writer = ["ignoringFurtherWrites" => true];
    if (str_starts_with(strtolower($rawUrl), '/webresource.axd')) {
        $writer["ignoringFurtherWrites"] = false;
    }
    $buffer .= $writer["ignoringFurtherWrites"] ? "" : "!";
    echo $buffer;
}
?>
--EXPECT--
