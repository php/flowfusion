--TEST--
Translated seed (inferredbugs-csharp: SiaNet/13)
--FILE--
<?php
$lr = 0.01;
$beta1 = 0.9;
$beta2 = 0.999;
$decayRate = 0.0;
$epsilon = 1e-7;
$amsgrad = true;

$params = [
    'w' => ['data' => [1.0, -2.0, 3.0], 'grad' => [0.1, -0.2, 0.3]],
    'b' => ['data' => [0.5], 'grad' => [0.05]],
];

$ms = [];
$vs = [];
$vhats = [];

for ($iteration = 1; $iteration <= 3; $iteration++) {
    if ($decayRate > 0) {
        $lr *= (1 / 1 + $decayRate * $iteration);
    }
    $t = (float)$iteration;
    $lr_t = $lr * sqrt(1.0 - pow($beta2, $t)) / (1.0 - pow($beta1, $t));

    foreach ($params as $name => &$param) {
        if (!isset($ms[$name])) $ms[$name] = array_fill(0, count($param['data']), 0.0);
        if (!isset($vs[$name])) $vs[$name] = array_fill(0, count($param['data']), 0.0);
        if (!isset($vhats[$name])) $vhats[$name] = array_fill(0, $amsgrad ? count($param['data']) : 1, 0.0);

        $m_t = [];
        $v_t = [];
        foreach ($param['data'] as $i => $x) {
            $g = $param['grad'][$i];
            $m_t[$i] = $beta1 * $ms[$name][$i] + (1.0 - $beta1) * $g;
            $v_t[$i] = $beta2 * $vs[$name][$i] + (1.0 - $beta2) * ($g * $g);
            $den = sqrt($v_t[$i]) + $epsilon;
            if ($amsgrad) {
                $vhats[$name][$i] = max($vhats[$name][$i], $v_t[$i]);
                $den = sqrt($vhats[$name][$i]) + $epsilon;
            }
            $param['data'][$i] -= $lr_t * $m_t[$i] / $den;
        }
        $ms[$name] = $m_t;
        $vs[$name] = $v_t;
    }
    unset($param);
}

echo "ok\n";
?>
--EXPECT--
