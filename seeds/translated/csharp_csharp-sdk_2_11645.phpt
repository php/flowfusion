--TEST--
Translated seed (inferredbugs-csharp: csharp-sdk/2)
--FILE--
<?php
$policy = [
    'scope' => 'lskjd:lskd',
    'callbackUrl' => 'callbackUrl.com',
    'returnUrl' => 'returnUrl.com',
];
try {
    json_encode($policy, JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
}

$policy = [
    'scope' => 'bucket',
    'callbackUrl' => 'callbackUrl.com',
];
json_encode($policy, JSON_THROW_ON_ERROR);

$policy = [
    'scope' => 'bucket',
    'asyncOps' => '',
    'callBackBody' => 'uid=123',
    'callBackUrl' => 'www.qiniu.com',
    'detectMime' => 1,
    'fsizeLimit' => 4096,
    'insertOnly' => 1,
    'persistentNotifyUrl' => 'www.yourdomain.com/persistentNotifyUrl',
    'persistentOps' => 'avthumb/m3u8/preset/video_16x9_440k',
    'deadline' => 0,
];
json_encode($policy, JSON_THROW_ON_ERROR);
echo "done\n";
?>
--EXPECT--
