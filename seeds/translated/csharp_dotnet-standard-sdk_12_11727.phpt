--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/12)
--FILE--
<?php
$content = "The IBM Watson™ Personality Insights service provides a Representational State Transfer (REST) Application Programming Interface (API) that enables applications to derive insights from social media, enterprise data, or other digital communications. The service uses linguistic analytics to infer individuals' intrinsic personality characteristics, including Big Five, Needs, and Values, from digital communications such as email, text messages, tweets, and forum posts. The service can automatically infer, from potentially noisy social media, portraits of individuals that reflect their personality characteristics. The service can report consumption preferences based on the results of its analysis, and for JSON content that is timestamped, it can report temporal behavior.";

$items = [
    [
        'contenttype' => 'text/plain',
        'language' => 'en',
        'content' => $content
    ]
];

$payload = [
    'contentItems' => $items
];

$json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$decoded = json_decode($json, true);

if (!isset($decoded['contentItems'][0]['content']) || $decoded['contentItems'][0]['content'] !== $content) {
    exit(1);
}

$csv = "trait,score\nopenness,0.73\nconscientiousness,0.61\n";
$fp = fopen('php://temp', 'r+');
fwrite($fp, $csv);
rewind($fp);
$text = stream_get_contents($fp);
fclose($fp);

if (strlen($text) > 0 && $text[0] === '{') {
    exit(1);
}
?>
--EXPECT--
