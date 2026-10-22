--TEST--
Translated seed (inferredbugs-csharp: html2openxml/1)
--FILE--
<?php
$htmlTag = '<span style="text-decoration&#58;underline&#59;color:red" class="a  b" data-x="1" data-y=2 disabled>';
$attrs = [];
if ($htmlTag !== '') {
    if (preg_match_all('/(?<tag>\w+)=(?:"|\')\s*(?<val>\#?.*?)(?:\k<0>|>|"|\' )|(?<tag2>\w+)=(?<val2>\w+)|\b(?<tag3>\w+)\b/sx', $htmlTag, $m, PREG_SET_ORDER, 0)) {
        foreach ($m as $r) {
            $k = $r['tag'] ?? ($r['tag2'] ?? $r['tag3']);
            $v = $r['val'] ?? ($r['val2'] ?? '');
            $attrs[$k] = $v;
        }
    }
}
$style = html_entity_decode($attrs['style'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
$styleAttrs = [];
if ($style !== '' && preg_match_all('/(?<name>.+?):\s*(?<val>[^;]+);*\s*/s', $style, $m, PREG_SET_ORDER)) {
    foreach ($m as $r) $styleAttrs[$r['name']] = $r['val'];
}
$classes = preg_split('/\s+/', $attrs['class'] ?? '', -1, PREG_SPLIT_NO_EMPTY);
$int = filter_var($attrs['data-y'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
$border = ($attrs['border'] ?? '') . ($attrs['border-width'] ?? '') . ($attrs['border-color'] ?? '') . ($attrs['border-style'] ?? '');
echo ($styleAttrs['text-decoration'] ?? '') . "\n";
echo implode(',', $classes) . "\n";
echo ($int === null ? '' : $int) . "\n";
echo $border . "\n";
?>
--EXPECT--
