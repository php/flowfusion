--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/5)
--FILE--
<?php
$xml = '<Bucket><name>default</name><nodes><node><hostname>127.0.0.1</hostname></node></nodes></Bucket>';
$doc = new DOMDocument();
$doc->loadXML($xml);
$xpath = new DOMXPath($doc);
$bucket = $xpath->evaluate('string(/Bucket/name)');
$node = $xpath->evaluate('string(/Bucket/nodes/node/hostname)');
if ($bucket === '' || $node === '') {
    throw new RuntimeException('Invalid config');
}
$config = ['bucket' => $bucket, 'node' => $node];
$type = $config['bucket'] === 'default' ? 'Couchbase' : 'Unknown';
if ($type !== 'Couchbase') {
    throw new RuntimeException('Wrong bucket type');
}
$nullConfig = null;
try {
    $x = $nullConfig['bucket'];
    $type2 = $x === 'default' ? 'Couchbase' : 'Unknown';
    if ($type2 !== 'Couchbase') {
        throw new RuntimeException('Wrong bucket type');
    }
} catch (Throwable $e) {
}
echo "";
?>
--EXPECT--
