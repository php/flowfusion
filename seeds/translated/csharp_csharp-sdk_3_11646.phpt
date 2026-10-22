--TEST--
Translated seed (inferredbugs-csharp: csharp-sdk/3)
--FILE--
<?php
$policy = ['scope' => 'bucket', 'callBackUrl' => 'www.qiniu.com', 'callBackBody' => 'uid=123', 'deadline' => 0, 'insertOnly' => 1, 'detectMime' => 1, 'fsizeLimit' => 4096, 'fsizeMin' => 0, 'persistentNotifyUrl' => 'www.yourdomain.com/persistentNotifyUrl', 'persistentOps' => 'avthumb/m3u8/preset/video_16x9_440k', 'callbackHost' => '180.97.211.38', 'callbackBodyType' => 'application/json', 'callbackFetchKey' => 0];
echo json_encode($policy, JSON_UNESCAPED_SLASHES), PHP_EOL;

$tmp = tempnam(sys_get_temp_dir(), 'qiniu_');
file_put_contents($tmp, str_repeat('a', 10240));
$h = hash_file('crc32b', $tmp);
$key = $h;
$ok = is_string($h) && $key === $h && filesize($tmp) === 10240;
unlink($tmp);
echo ($ok ? "OK" : "FAIL"), PHP_EOL;
?>
--EXPECT--
