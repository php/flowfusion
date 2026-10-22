--TEST--
Translated seed (inferredbugs-csharp: SourceBrowser/1)
--FILE--
<?php
declare(strict_types=1);

$usages = [];
$serviceStarted = new DateTimeImmutable('now', new DateTimeZone('UTC'));
$requestsServed = 0;

$symbol = $argv[1] ?? 'abc';

if ($symbol === null || strlen($symbol) < 3) {
    echo "Enter at least 3 characters.\n";
    exit(0);
}

if (strlen($symbol) > 260) {
    echo "Query string is too long (maximum is 260 characters, input is " . strlen($symbol) . " characters)\n";
    exit(0);
}

$symbolId = null;
if (preg_match('/^\d+$/', $symbol) && $symbol !== '') {
    $symbolId = (int)$symbol;
} else {
    $symbolId = unpack('P', md5($symbol, true))[1];
}

$index = [
    'symbolsById' => [
        $symbolId => 0,
    ],
    'symbols' => [
        ['name' => $symbol, 'url' => 'https://example.invalid/' . rawurlencode($symbol)],
    ],
];

if (!array_key_exists($symbolId, $index['symbolsById'])) {
    echo "Not Found\n";
    exit(0);
}

$position = $index['symbolsById'][$symbolId];
if ($position < 0 || $position >= count($index['symbols'])) {
    echo "Not Found\n";
    exit(0);
}

$symbolInfo = $index['symbols'][$position];
$url = $symbolInfo['url'];

$userName = 'user';
$requests = $usages[$userName] ?? 0;
$requests++;
$requestsServed++;
$usages[$userName] = $requests;

echo $url . "\n";
echo 'Since ' . $serviceStarted->setTimezone(new DateTimeZone(date_default_timezone_get()))->format('m') . ":\n";
echo '  ' . count($usages) . " unique users\n";
echo '  ' . $requestsServed . " requests served.\n";
?>
--EXPECT--
