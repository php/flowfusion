--TEST--
Translated seed (inferredbugs-csharp: Hangfire.HttpJob/2)
--FILE--
<?php
$settings = [
    'TablePrefix' => 'hf_',
    'StartUpPath' => '/hangfire',
    'ReadOnlyPath' => '/hangfire-ro',
    'JobQueues' => ['Default', 'default', 'BATCH', 'batch', ''],
    'HttpAuthInfo' => [
        'SslRedirect' => false,
        'RequireSsl' => false,
        'LoginCaseSensitive' => true,
        'IsOpenLogin' => true,
        'Users' => [
            ['Login' => 'Admin', 'PasswordClear' => 'secret'],
            ['Login' => 'admin', 'PasswordClear' => 'secret2'],
        ],
    ],
    'WorkerCount' => 40,
    'DisplayStorageConnectionString' => false,
];

$langStr = getenv('HANGFIRE_LANG');
if ($langStr !== false && $langStr !== '') {
    setlocale(LC_ALL, $langStr);
    $old = locale_get_default();
    locale_set_default($langStr);
}

$queues = [];
foreach ($settings['JobQueues'] as $q) {
    $q = strtolower($q);
    if (!in_array($q, $queues, true)) $queues[] = $q;
}

$workerCount = max((int)trim((string)shell_exec('getconf _NPROCESSORS_ONLN 2>/dev/null') ?: '1'), $settings['WorkerCount']);

$users = [];
if ($settings['HttpAuthInfo']['IsOpenLogin'] && !empty($settings['HttpAuthInfo']['Users'])) {
    foreach ($settings['HttpAuthInfo']['Users'] as $u) {
        $users[] = [
            'Login' => $u['Login'],
            'Password' => $u['PasswordClear'],
            'PasswordBytes' => array_values(unpack('C*', $u['PasswordClear'])),
        ];
    }
}

$result = [
    'prefix' => $settings['TablePrefix'],
    'queues' => $queues,
    'workerCount' => $workerCount,
    'authUsers' => $users,
    'readOnly' => $settings['ReadOnlyPath'] !== '',
    'startup' => $settings['StartUpPath'],
];

echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
?>
--EXPECT--
