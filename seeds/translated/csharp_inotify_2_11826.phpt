--TEST--
Translated seed (inferredbugs-csharp: inotify/2)
--FILE--
<?php
$tokens = [
    'tok1' => true,
    'tok2' => false,
];
$keys = [
    'dev1' => true,
    'dev2' => false,
];

$cases = [
    ['tok1', 'title', 'data', null],
    ['tok1', 'title', 'data', 'dev1'],
    ['tok1', 'title', 'data', 'dev2'],
    ['tok1', 'title', 'data', 'dev3'],
    ['tok2', 'title', 'data', null],
    ['tok3', 'title', 'data', null],
];

foreach ($cases as [$token, $title, $data, $key]) {
    if (array_key_exists($token, $tokens)) {
        $hasActive = $tokens[$token];
        if (!$hasActive) {
            continue;
        }

        if ($key !== null && $key !== '') {
            if (array_key_exists($key, $keys)) {
                $isActive = $keys[$key];
                if (!$isActive) {
                    continue;
                }
            } else {
                continue;
            }
        }

        $message = [
            'Token' => $token,
            'Title' => $title,
            'Data' => $data,
            'Key' => $key,
        ];

        if (($message['Token'] ?? '') !== '') {
            continue;
        }
    }
}
?>
--EXPECT--
