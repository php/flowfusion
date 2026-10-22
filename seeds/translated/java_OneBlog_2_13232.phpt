--TEST--
Translated seed (inferredbugs-java: OneBlog/2)
--FILE--
<?php

$LT = "<";
$LT_CHAR = "&lt;";
$GT = ">";
$GT_CHAR = "&gt;";
$AMP = "&";
$AMP_CHAR = "&amp;";
$APOS = "'";
$APOS_CHAR = "&apos;";
$QUOT = '"';
$QUOT_CHAR = '&quot;';

$templateContent = "<xml><value>${value}</value></xml>";
$dataMap = ['value' => "<value with special characters & ' \" >"];

function filterXmlString($str) {
    global $LT, $LT_CHAR, $GT, $GT_CHAR, $AMP, $AMP_CHAR, $APOS, $APOS_CHAR, $QUOT, $QUOT_CHAR;
    $str = str_replace($LT, $LT_CHAR, $str);
    $str = str_replace($GT, $GT_CHAR, $str);
    $str = str_replace($AMP, $AMP_CHAR, $str);
    $str = str_replace($APOS, $APOS_CHAR, $str);
    $str = str_replace($QUOT, $QUOT_CHAR, $str);
    return $str;
}

$newMap = [];
foreach ($dataMap as $key => $value) {
    if ($value !== null) {
        $value = trim($value);
        $value = filterXmlString($value);
        $newMap[$key] = $value;
    }
}

$output = $templateContent;
foreach ($newMap as $key => $value) {
    $output = str_replace('${' . $key . '}', $value, $output);
}

echo $output;
--EXPECT--
