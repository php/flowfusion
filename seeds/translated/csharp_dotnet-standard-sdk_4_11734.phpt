--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/4)
--FILE--
<?php
$credentials = [
    'text-to-speech-sdk' => [
        [
            'credentials' => [
                'url' => 'https://example.invalid',
                'username' => 'user',
                'password' => 'pass'
            ]
        ]
    ]
];

$json = json_encode(['VCAP_SERVICES' => $credentials], JSON_UNESCAPED_SLASHES);
$data = json_decode($json, true);

$svc = $data['VCAP_SERVICES']['text-to-speech-sdk'][0]['credentials'];
$endpoint = $svc['url'];
$username = $svc['username'];
$password = $svc['password'];

$voices = [
    ['name' => 'en-US_AllisonVoice'],
    ['name' => 'en-US_MichaelVoice']
];
$allison = null;
foreach ($voices as $v) {
    if ($v['name'] === 'en-US_AllisonVoice') {
        $allison = $v;
        break;
    }
}

$text = "Hello, welcome to the Watson dotnet SDK!";
$audio = strrev($text);

$pronunciation = ['pronunciation' => 'eye bee em'];

$customizationId = 'dotnet-sdk-voice-model';
$voiceModel = [
    'customization_id' => $customizationId,
    'name' => 'dotnet-sdk-voice-model',
    'description' => 'Custom voice model for .NET SDK integration tests.',
    'words' => [
        ['word' => 'hello', 'translation' => 'hullo'],
        ['word' => 'goodbye', 'translation' => 'gbye'],
        ['word' => 'hi', 'translation' => 'ohioooo']
    ]
];

$updatedVoiceModel = $voiceModel;
$updatedVoiceModel['name'] = 'dotnet-sdk-voice-model-updated';
$updatedVoiceModel['description'] = 'Custom voice model for .NET SDK integration tests. Updated.';

$words = [
    'hello' => 'hullo',
    'goodbye' => 'gbye',
    'hi' => 'ohioooo'
];

$listWords = $words;
$getWord = $listWords['hello'];
$listWords['IBM'] = 'eye bee m';
unset($listWords['hi']);

echo $endpoint . "\n";
echo $username . "\n";
echo $password . "\n";
echo $allison['name'] . "\n";
echo $audio . "\n";
echo $pronunciation['pronunciation'] . "\n";
echo $updatedVoiceModel['name'] . "\n";
echo $updatedVoiceModel['description'] . "\n";
echo count($updatedVoiceModel['words']) . "\n";
echo $getWord . "\n";
echo count($listWords) . "\n";
?>
--EXPECT--
