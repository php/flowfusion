--TEST--
Translated seed (inferredbugs-csharp: silky/2)
--FILE--
<?php
$clients = [];
$conn = "a;b;c";
foreach (explode(";", $conn) as $s) {
    if (!array_key_exists($s, $clients)) {
        $clients[$s] = ["connected" => true, "id" => $s];
    }
}

$healthCheckInterval = 1;
$connectionTimeout = 1;

for ($i = 0; $i < 3; $i++) {
    foreach ($clients as $k => $client) {
        if (mt_rand(0, 1) === 0) {
            unset($clients[$k]);
        }
    }
    foreach (explode(";", $conn) as $s) {
        if (!array_key_exists($s, $clients)) {
            $clients[$s] = ["connected" => true, "id" => $s];
        }
    }
}

if (count($clients) <= 0) {
    throw new Exception("当前没有可用服务注册中心");
}

if (count($clients) === 1) {
    $one = reset($clients);
} else {
    $values = array_values($clients);
    $one = $values[random_int(0, count($clients) - 1)];
}

$list = array_values($clients);
?>
--EXPECT--
