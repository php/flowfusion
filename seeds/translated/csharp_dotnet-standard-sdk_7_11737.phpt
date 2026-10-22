--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/7)
--FILE--
<?php
$credentials = [];
$vcap = [
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
$credential = $vcap['text-to-speech-sdk'][0]['credentials'];
$endpoint = $credential['url'];
$username = $credential['username'];
$password = $credential['password'];

$voices = [
    ['name' => 'en-US_AllisonVoice'],
    ['name' => 'en-US_MichaelVoice']
];
$voice = null;
foreach ($voices as $v) {
    if ($v['name'] === 'en-US_AllisonVoice') {
        $voice = $v;
        break;
    }
}
if ($voice === null || $voice['name'] === '') {
    exit(1);
}

$text = "Hello, welcome to the Watson dotnet SDK!";
$audio = base64_encode($text);
if ($audio === '') {
    exit(1);
}

$pronunciation = ['pronunciation' => 'eye bee em'];
if ($pronunciation['pronunciation'] === '') {
    exit(1);
}

$customizations = [];
$customizationId = 'model-1';
$customizations[$customizationId] = [
    'name' => 'dotnet-sdk-voice-model',
    'description' => 'Custom voice model for .NET SDK integration tests.',
    'words' => [
        'hello' => 'hullo',
        'goodbye' => 'gbye',
        'hi' => 'ohioooo'
    ]
];

$customizations[$customizationId]['name'] = 'dotnet-sdk-voice-model-updated';
$customizations[$customizationId]['description'] = 'Custom voice model for .NET SDK integration tests. Updated.';
$customizations[$customizationId]['words']['IBM'] = 'eye bee m';
unset($customizations[$customizationId]['words']['hi']);

$listWords = $customizations[$customizationId]['words'];
$getWord = $listWords['hello'] ?? null;
if ($getWord !== 'hullo') {
    exit(1);
}

$checkAddWord = $customizations[$customizationId]['words'];
if (count($checkAddWord) !== 4) {
    exit(1);
}

$checkDeleteWord = $customizations[$customizationId]['words'];
if (count($checkDeleteWord) !== 4) {
    exit(1);
}

unset($customizations[$customizationId]);
echo "";
?>
--EXPECT--
