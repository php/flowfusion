--TEST--
Translated seed (inferredbugs-csharp: Flurl/1)
--FILE--
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'data:text/plain,ok');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Test: 1']);
curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) {
    return strlen($header);
});
curl_exec($ch);
curl_close($ch);
echo "ok\n";
?>
--EXPECT--
