--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/5)
--FILE--
<?php
$voiceModel = [
    'name' => 'dotnet-sdk-voice-model',
    'description' => 'Custom voice model for .NET SDK integration tests.',
    'language' => 'en-US',
    'words' => [
        ['word' => 'hello', 'translation' => 'hullo'],
        ['word' => 'goodbye', 'translation' => 'gbye'],
        ['word' => 'hi', 'translation' => 'ohioooo'],
    ],
];

$customizations = [];
$customizationId = 'cust-' . bin2hex(random_bytes(4));
$customizations[$customizationId] = [
    'name' => $voiceModel['name'],
    'description' => $voiceModel['description'],
    'words' => $voiceModel['words'],
];

$listedModels = array_values($customizations);
$gotModel = $customizations[$customizationId] ?? null;

$customizations[$customizationId]['name'] = 'dotnet-sdk-voice-model-updated';
$customizations[$customizationId]['description'] = 'Custom voice model for .NET SDK integration tests. Updated.';
$customizations[$customizationId]['words'][] = ['word' => 'IBM', 'translation' => 'eye bee m'];

$listedWords = $customizations[$customizationId]['words'];
$helloTranslation = null;
foreach ($listedWords as $w) {
    if ($w['word'] === 'hello') {
        $helloTranslation = $w['translation'];
        break;
    }
}

foreach ($customizations[$customizationId]['words'] as $i => $w) {
    if ($w['word'] === 'hi') {
        unset($customizations[$customizationId]['words'][$i]);
        break;
    }
}
$customizations[$customizationId]['words'] = array_values($customizations[$customizationId]['words']);

$finalWords = $customizations[$customizationId]['words'];
unset($customizations[$customizationId]);

echo "";
?>
--EXPECT--
