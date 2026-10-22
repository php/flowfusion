--TEST--
Translated seed (inferredbugs-csharp: jaeger-client-csharp/4)
--FILE--
<?php
$sample = true;
$tags = [
    'sampler.type' => 'const',
    'sampler.param' => $sample,
];

$sampler = ['decision' => $sample];

$isSampled = [
    'sampled' => $sampler['decision'],
    'tags' => $tags,
];

if ($sampler['decision'] !== $sample) {
    exit(1);
}
if ($isSampled['sampled'] !== $sample) {
    exit(1);
}
if ($isSampled['tags'] !== $tags) {
    exit(1);
}
?>
--EXPECT--
