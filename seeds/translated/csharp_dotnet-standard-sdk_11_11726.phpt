--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/11)
--FILE--
<?php
$json = @file_get_contents('profile.json');
if ($json !== false) {
    json_decode($json, true);
}
$contentToProfile = "The IBM Watson™ Personality Insights service provides a Representational State Transfer (REST) Application Programming Interface (API) that enables applications to derive insights from social media, enterprise data, or other digital communications. The service uses linguistic analytics to infer individuals' intrinsic personality characteristics, including Big Five, Needs, and Values, from digital communications such as email, text messages, tweets, and forum posts. The service can automatically infer, from potentially noisy social media, portraits of individuals that reflect their personality characteristics. The service can report consumption preferences based on the results of its analysis, and for JSON content that is timestamped, it can report temporal behavior.";
$content = ['contentItems' => [['contenttype' => 'text/plain', 'language' => 'en', 'content' => $contentToProfile]]];
$jsonOut = json_encode($content, JSON_UNESCAPED_UNICODE);
echo $jsonOut . PHP_EOL;
?>
--EXPECT--
