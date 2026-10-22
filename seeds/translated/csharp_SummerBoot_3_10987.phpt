--TEST--
Translated seed (inferredbugs-csharp: SummerBoot/3)
--FILE--
<?php
$headers = ['a' => 'a', 'b' => 'b'];
$payload = [
    'name' => 'hzp2',
    'age' => 10,
    'form' => ['name' => 'form'],
    'query' => '何泽平',
    'headers' => $headers,
    'auth' => base64_encode("token-zpz5g:l5v5r8v2j5f47cvd9lqxvhn5t4grr2np676jxxcj4bj6p4x8jx2vf4"),
];
$stream = fopen('php://temp', 'r+');
fwrite($stream, json_encode($payload, JSON_UNESCAPED_UNICODE));
rewind($stream);
$copy = fopen('php://temp', 'r+');
stream_copy_to_stream($stream, $copy);
rewind($copy);
$data = stream_get_contents($copy);
echo "ok";
?>
--EXPECT--
