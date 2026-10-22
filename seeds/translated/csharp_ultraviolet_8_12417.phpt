--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/8)
--FILE--
<?php
$vertexUsageCount = 4;
$usageIndexCount = 2;
$vertexAttributeNames = [];
$usages = ['Position', 'Normal', 'TexCoord', 'Color'];
for ($u = 0; $u < count($usages); $u++) {
    for ($i = 0; $i < $usageIndexCount; $i++) {
        $vertexAttributeNames[] = 'uv_' . $usages[$u] . $i;
    }
}
$vertexDeclaration = [
    ['Usage' => 0, 'Index' => 0, 'Format' => 'Vector3'],
    ['Usage' => 2, 'Index' => 0, 'Format' => 'Vector2'],
    ['Usage' => 3, 'Index' => 0, 'Format' => 'Color'],
];
$buffers = [
    ['OpenGLName' => 11, 'VertexDeclaration' => $vertexDeclaration, 'VertexStride' => 24],
    ['OpenGLName' => 12, 'VertexDeclaration' => $vertexDeclaration, 'VertexStride' => 24],
];
$programAttributes = ['uv_Position0' => 0, 'uv_TexCoord0' => 1, 'uv_Color0' => 2];
$activeAttributes = count($programAttributes);
$enabled = array_fill(0, 8, false);
for ($i = 0; $i < $activeAttributes; $i++) $enabled[$i] = false;
$position = 0;
foreach ($buffers as $vbuffer) {
    foreach ($vbuffer['VertexDeclaration'] as $element) {
        $name = $vertexAttributeNames[($element['Usage'] * $usageIndexCount) + $element['Index']];
        switch ($element['Format']) {
            case 'Single': $size = 1; $stride = 4; break;
            case 'Vector2': $size = 2; $stride = 8; break;
            case 'Vector3': $size = 3; $stride = 12; break;
            case 'Vector4': $size = 4; $stride = 16; break;
            case 'Color': $size = 4; $stride = 4; break;
            default: $size = 0; $stride = 0; break;
        }
        if (isset($programAttributes[$name])) {
            $location = $programAttributes[$name];
            $enabled[$location] = true;
        }
        $position += $stride;
    }
}
echo "";
?>
--EXPECT--
