--TEST--
Translated seed (inferredbugs-csharp: jose-jwt/2)
--FILE--
<?php
$tokens = [
    'eyJhbGciOiJub25lIn0.eyJoZWxsbyI6ICJ3b3JsZCJ9.',
    'eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJoZWxsbyI6IndvcmxkIn0.',
    'eyJhbGciOiJIUzI1NiIsImN0eSI6InRleHRcL3BsYWluIn0.eyJoZWxsbyI6ICJ3b3JsZCJ9.chIoYWrQMA8XL5nFz6oLDJyvgHk2KA4BrFGrKymjC8E',
    'eyJhbGciOiJIUzM4NCIsImN0eSI6InRleHRcL3BsYWluIn0.eyJoZWxsbyI6ICJ3b3JsZCJ9.McDgk0h4mRdhPM0yDUtFG_omRUwwqVS2_679Yeivj-a7l6bHs_ahWiKl1KoX_hU_',
    'eyJhbGciOiJIUzUxMiIsImN0eSI6InRleHRcL3BsYWluIn0.eyJoZWxsbyI6ICJ3b3JsZCJ9.9KirTNe8IRwFCBLjO8BZuXf3U2ZVagdsg7F9ZsvMwG3FuqY9W0vqwjzPOjLqPN-GkjPm6C3qWPnINhpr5bEDJQ',
];
foreach ($tokens as $t) {
    $p = explode('.', $t);
    $json = base64_decode(strtr($p[1], '-_', '+/'));
    json_decode($json, true);
}
$payload = ['hello' => 'world'];
$json = json_encode($payload, JSON_UNESCAPED_SLASHES);
$header = ['typ' => 'JWT', 'alg' => 'none'];
$token = rtrim(strtr(base64_encode(json_encode($header, JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=') . '.' . rtrim(strtr(base64_encode($json), '+/', '-_'), '=') . '.';
echo $token, PHP_EOL;
echo $json, PHP_EOL;
?>
--EXPECT--
