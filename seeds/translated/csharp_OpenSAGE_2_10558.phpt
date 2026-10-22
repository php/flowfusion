--TEST--
Translated seed (inferredbugs-csharp: OpenSAGE/2)
--FILE--
<?php
class GraphicsDevice {
    public function dispose(): void {}
}

$graphicsDevice = new GraphicsDevice();
$graphicsDevice->dispose();
$graphicsDevice = null;
?>
--EXPECT--
