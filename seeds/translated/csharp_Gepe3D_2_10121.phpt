--TEST--
Translated seed (inferredbugs-csharp: Gepe3D/2)
--FILE--
<?php
$w = 1600;
$h = 900;
$t = "Gepe3D";

$scene = null;
$scene = new class {
    public function Update() {}
    public function Render() {}
};

for ($i = 0; $i < 3; $i++) {
    if ($i === 0) {
        $keyboardState = ['Escape' => false];
        $mouseState = ['x' => 0, 'y' => 0];
    } else {
        $keyboardState = ['Escape' => ($i === 2)];
        $mouseState = ['x' => $i, 'y' => -$i];
    }

    if (!empty($keyboardState['Escape'])) {
        break;
    }

    $scene->Update();
    $scene->Render();
}

echo $t, " ", $w, "x", $h, PHP_EOL;
--EXPECT--
