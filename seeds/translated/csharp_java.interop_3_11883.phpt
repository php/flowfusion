--TEST--
Translated seed (inferredbugs-csharp: java.interop/3)
--FILE--
<?php
$cases = [
    [null, null],
    ["TypeOnly", null],
    ["pkg/Outer.Inner.Member", null],
    ["pkg/Outer$Inner.Member", null],
    ["I:pkg/Outer.Inner.Member", null],
    ["pkg/Outer.Inner$Nested.Member", null],
];

foreach ($cases as [$sig, $dummy]) {
    $javaSignature = $sig;
    if ($javaSignature === null || $javaSignature === '') {
        $javaPackage = '';
        $javaType = '';
        $javaName = '';
    } else {
        $index = strrpos($javaSignature, '/');
        $dotIndex = strrpos($javaSignature, '.');
        $javaPackage = $index === false ? '' : substr($javaSignature, 0, $index);
        $javaType = ($index === false || $dotIndex === false) ? '' : substr($javaSignature, $index + 1, $dotIndex - $index - 1);
        $javaName = $dotIndex === false ? '' : substr($javaSignature, $dotIndex + 1);
        $javaSignature = ($javaPackage === '' ? '' : $javaPackage . '/') . str_replace('.', '$', $javaType) . '.' . $javaName;
        $javaSignature = ltrim($javaSignature, '/');
    }
    echo $javaSignature, "\n";
}
?>
--EXPECT--
