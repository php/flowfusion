--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/5)
--FILE--
<?php
$version = getenv('CASSANDRA_VERSION') ?: '1.2.3-beta';
if (preg_match('/^\d\.\d\.\d(-\w+)?$/', $version)) {
    $cassandraDir = null;
    $cassandraVersion = '-v ' . $version;
} else {
    $cassandraDir = getenv('CASSANDRA_DIR') ?: '/tmp/cassandra';
    $cassandraVersion = null;
}
$ipPrefix = getenv('IP_PREFIX') ?: '127.0.0.';
$host = getenv('SSH_HOST') ?: 'localhost';
$port = (int)(getenv('SSH_PORT') ?: 22);
$username = getenv('SSH_USERNAME') ?: 'user';
$password = getenv('SSH_PASSWORD') ?: 'pass';

$cmds = [
    'Create test -n 1 -s -i ' . $ipPrefix . ' -b ' . $cassandraVersion,
    'start',
    'node1 start',
    'node1 ring',
    'add node2 -i ' . $ipPrefix . '2 -j ' . (7000 + 100 * 2) . ' -b',
    'node2 start',
    'node2 decommission',
    'stop --not-gently',
    'remove',
];
foreach ($cmds as $cmd) {
    $s = "ccm " . $cmd;
    strlen($s);
}
echo "";
?>
--EXPECT--
