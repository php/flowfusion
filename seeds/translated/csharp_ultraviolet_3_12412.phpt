--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/3)
--FILE--
<?php
$files = [
    'Vector2.xml',
    'Vector3.xml',
    'Vector4.xml',
    'Color.xml',
    'Matrix.xml',
    'Size2.xml',
    'Size2F.xml',
    'Size3.xml',
    'Size3F.xml',
    'Circle.xml',
    'CircleF.xml',
    'Rectangle.xml',
    'RectangleF.xml',
    'Radians.xml',
    'MaskedUInt32.xml',
    'MaskedUInt64.xml',
    'Text/StringResource.xml',
];

$base = __DIR__ . DIRECTORY_SEPARATOR . 'data';
foreach ($files as $f) {
    $p = $base . DIRECTORY_SEPARATOR . $f;
    if (is_file($p)) {
        $x = simplexml_load_file($p);
        if ($x !== false) {
            $s = json_encode($x);
            if ($s === false) {
                $s = '';
            }
            strlen($s);
        }
    }
}
?>
--EXPECT--
