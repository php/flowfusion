--TEST--
Translated seed (inferredbugs-csharp: Rhisis/1)
--FILE--
<?php
$path = getenv('DB_CONFIG');
$config = ['Database' => 'default'];
if ($path !== false && $path !== '') {
    $data = @file_get_contents($path);
    if ($data !== false) {
        $decoded = json_decode($data, true);
        if (is_array($decoded) && isset($decoded['Database'])) $config = $decoded;
    }
}
$exists = ($path === false || $path === '') ? true : is_string($config['Database']) && $config['Database'] !== '';
if (!$exists) {
    throw new InvalidArgumentException("The database '{$config['Database']}' doesn't exists.");
}
echo $config['Database'];
?>
--EXPECT--
