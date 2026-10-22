--TEST--
Translated seed (inferredbugs-csharp: sentry-dotnet/2)
--FILE--
<?php
$limits = [];
$instant = time();

$items = [
    ['type' => 'event', 'category' => 'error'],
    ['type' => 'transaction', 'category' => 'transaction'],
    ['type' => 'attachment', 'category' => 'attachment'],
];

$header = "60:error;transaction,10:attachment,120:error";
foreach (explode(',', $header) as $part) {
    [$retry, $cats] = explode(':', $part, 2);
    $expiry = $instant + (int)$retry;
    foreach (explode(';', $cats) as $cat) {
        $limits[$cat] = $expiry;
    }
}

$processed = [];
foreach ($items as $item) {
    $limited = false;
    foreach ($limits as $cat => $expiry) {
        if ($expiry > $instant && $cat === $item['category']) {
            $limited = true;
            break;
        }
    }
    if (!$limited) {
        $processed[] = $item;
    }
}

if (!$processed) {
    exit(0);
}

$response = json_decode('{"detail":"rejected"}', true);
$statusCode = 400;

if ($statusCode === 200) {
    echo "ok\n";
} else {
    $errorMessage = $response['detail'] ?? 'No message';
    echo $errorMessage, "\n";
}
?>
--EXPECT--
