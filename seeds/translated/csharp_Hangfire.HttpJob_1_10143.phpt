--TEST--
Translated seed (inferredbugs-csharp: Hangfire.HttpJob/1)
--FILE--
<?php
$queues = ['default', 'critical', 'low'];
$startUpPath = trim((string)($_ENV['HangfireStartUpPath'] ?? ''));
if ($startUpPath === '') $startUpPath = '/job';
$readOnlyPath = trim((string)($_ENV['HangfireReadOnlyPath'] ?? ''));
$user = (string)($_ENV['HangfireUserName'] ?? '');
$pwd = (string)($_ENV['HangfirePwd'] ?? '');
$port = (int)($_ENV['HangfireMail_Port'] ?? 25);
$useSsl = filter_var($_ENV['HangfireMail_UseSsl'] ?? false, FILTER_VALIDATE_BOOL);
$config = [
    'mysql' => (string)($_ENV['HangfireMysqlConnectionString'] ?? ''),
    'mail' => [
        'server' => (string)($_ENV['HangfireMail_Server'] ?? ''),
        'port' => $port,
        'use_ssl' => $useSsl,
        'user' => (string)($_ENV['HangfireMail_User'] ?? ''),
        'password' => (string)($_ENV['HangfireMail_Password'] ?? ''),
    ],
    'default_recurring_queue' => (string)($_ENV['DefaultRecurringQueueName'] ?? ''),
    'queues' => $queues,
    'paths' => [$startUpPath, $readOnlyPath],
    'locale' => 'zh',
    'worker_count' => max((int)($_ENV['NUMBER_OF_PROCESSORS'] ?? 1), 40),
    'timeouts' => [240, 15, 1800],
    'readonly' => $readOnlyPath !== '',
    'auth' => ($user !== '' && $pwd !== '') ? ['user' => $user, 'password' => $pwd] : null,
];
json_encode(['Success' => false, 'Info' => 'ok'], JSON_UNESCAPED_UNICODE);
echo "";
?>
--EXPECT--
