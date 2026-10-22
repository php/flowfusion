--TEST--
Translated seed (inferredbugs-csharp: OpenTouryo/1)
--FILE--
<?php
$curveMap = ['P-256' => 32, 'P-384' => 48, 'P-521' => 66];
foreach ([32, 48, 66] as $n) {
    $crv = array_search($n, $curveMap, true);
    $x = random_bytes($n);
    $y = random_bytes($n);
    $jwk = ['kty' => 'EC', 'alg' => 'ES256', 'crv' => $crv, 'x' => rtrim(strtr(base64_encode($x), '+/', '-_'), '='), 'y' => rtrim(strtr(base64_encode($y), '+/', '-_'), '=')];
    $thumb = ['crv' => $jwk['crv'], 'kty' => $jwk['kty'], 'x' => $jwk['x'], 'y' => $jwk['y']];
    $jwk['kid'] = rtrim(strtr(base64_encode(hash('sha256', json_encode($thumb, JSON_UNESCAPED_SLASHES), true)), '+/', '-_'), '=');
    $x2 = base64_decode(strtr($jwk['x'], '-_', '+/'));
    $y2 = base64_decode(strtr($jwk['y'], '-_', '+/'));
    $crv2 = [32 => 'P-256', 48 => 'P-384', 66 => 'P-521'][strlen($x2)];
    $jwk2 = ['kty' => $jwk['kty'], 'alg' => $jwk['alg'], 'crv' => $crv2, 'x' => rtrim(strtr(base64_encode($x2), '+/', '-_'), '='), 'y' => rtrim(strtr(base64_encode($y2), '+/', '-_'), '=')];
    $jwk2['kid'] = rtrim(strtr(base64_encode(hash('sha256', json_encode(['crv' => $jwk2['crv'], 'kty' => $jwk2['kty'], 'x' => $jwk2['x'], 'y' => $jwk2['y']], JSON_UNESCAPED_SLASHES), true)), '+/', '-_'), '=');
    json_encode($jwk2);
}
?>
--EXPECT--
