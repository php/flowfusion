--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/6)
--FILE--
<?php
$version = getenv('CASSANDRA_VERSION') ?: '3.11.10';
if (preg_match('/^\d\.\d\.\d(-\w+)?$/', $version)) {
    $cassandraDir = null;
    $cassandraVersion = '-v ' . $version;
} else {
    $cassandraDir = getenv('CASSANDRA_DIR') ?: '/tmp/cassandra';
    $cassandraVersion = null;
}
$ipPrefix = getenv('IP_PREFIX');
if ($ipPrefix === false || $ipPrefix === '') $ipPrefix = '127.0.0.';
$host = getenv('SSH_HOST') ?: 'localhost';
$port = (int)(getenv('SSH_PORT') ?: 22);
$username = getenv('SSH_USERNAME') ?: 'user';
$password = getenv('SSH_PASSWORD') ?: 'pass';
$ccmDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . bin2hex(random_bytes(8));
@mkdir($ccmDir, 0777, true);
$state = ['connected' => true, 'erroredOut' => false, 'schemaCreated' => false, 'cluster' => true, 'session' => true];
$cmds = [
    'ccm Create test 1 -b -i ' . $ipPrefix . '1 ' . $cassandraVersion,
    'ccm start',
    'ccm node1 ring',
    'ccm add node2 -i ' . $ipPrefix . '2 -j ' . (7000 + 100 * 2) . ' -b',
    'ccm node2 start',
    'ccm node2 decommission',
    'ccm stop',
    'ccm remove'
];
foreach ($cmds as $cmd) {
    if ($cmd === null) continue;
    $out = [];
    $rc = 0;
    @exec($cmd . ' 2>&1', $out, $rc);
    if ($rc !== 0) $state['erroredOut'] = true;
}
if ($state['cluster']) $state['cluster'] = false;
if (!$state['erroredOut']) {
    @rmdir($ccmDir);
}
?>
--EXPECT--
