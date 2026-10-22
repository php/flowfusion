--TEST--
Translated seed (inferredbugs-csharp: azure-signalr/3)
--FILE--
<?php
$url = "http://example.com/path";
$query = ["a" => ["1", "2"], "b" => ["", "x y"], "c" => ["?&="]];
$parts = parse_url($url);
$scheme = $parts["scheme"] ?? "http";
$host = $parts["host"] ?? "";
$port = isset($parts["port"]) ? ":" . $parts["port"] : "";
$path = $parts["path"] ?? "/";
$q = [];
if (isset($parts["query"]) && $parts["query"] !== "") parse_str($parts["query"], $q);
foreach ($query as $k => $vals) foreach ($vals as $v) $q[] = rawurlencode($k) . "=" . rawurlencode($v);
$uri = $scheme . "://" . $host . $port . $path . (count($q) ? "?" . implode("&", $q) : "");
$payload = ["target" => "m", "arguments" => [null, 0, "0", true, false, ["nested" => [1, 2, 3]]]];
$json = json_encode($payload, JSON_UNESCAPED_SLASHES);
$headers = [
    "Authorization: Bearer " . str_repeat("x", 3),
    "Accept: application/json",
    "User-Agent: product/1.0"
];
$ch = curl_init($uri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ["Content-Type: application/json", "Content-Length: " . strlen($json)]));
curl_exec($ch);
curl_close($ch);
?>
--EXPECT--
