--TEST--
Translated seed (inferredbugs-java: Saturn/4)
--FILE--
<?php
$curatorClientMap = [];
$registryCenterConfigurationMap = [];
$zkAddrToZkClusterMap = [];

function connect($nameAndNameSpace) {
    global $curatorClientMap, $registryCenterConfigurationMap;
    if (isset($curatorClientMap[$nameAndNameSpace])) {
        return $curatorClientMap[$nameAndNameSpace];
    }
    $config = findConfig($nameAndNameSpace);
    if ($config) {
        $client = $config['zkAddressList'];
        $curatorClientMap[$nameAndNameSpace] = ['connected' => true, 'client' => $client];
        return $curatorClientMap[$nameAndNameSpace];
    }
    return null;
}

function findConfig($nameAndNamespace) {
    global $registryCenterConfigurationMap, $zkAddrToZkClusterMap;
    foreach ($zkAddrToZkClusterMap as $zkCluster) {
        foreach ($registryCenterConfigurationMap[$zkCluster['zkAddr']] as $each) {
            if ($each['nameAndNamespace'] === $nameAndNamespace) {
                return $each;
            }
        }
    }
    return null;
}

function refreshRegistryCenterFromJsonFile() {
    global $registryCenterConfigurationMap, $zkAddrToZkClusterMap;
    $json = file_get_contents('path_to_file.json');
    $list = json_decode($json, true);
    $newRegMap = [];
    foreach ($list as $conf) {
        $zkAddr = $conf['zkAddressList'];
        $cluster = $zkAddrToZkClusterMap[$zkAddr] ?? null;
        if (!$cluster) {
            $cluster = ['zkAlias' => $conf['zkAlias'], 'zkAddr' => $zkAddr, 'curatorFramework' => true];
            $zkAddrToZkClusterMap[$zkAddr] = $cluster;
        }
        if (!isset($newRegMap[$zkAddr])) {
            $newRegMap[$zkAddr] = [];
        }
        $newRegMap[$zkAddr][] = $conf;
    }
    $registryCenterConfigurationMap = $newRegMap;
}

function refreshAll() {
    refreshRegistryCenterFromJsonFile();
}

refreshAll();
connect('exampleNameSpace');
--EXPECT--
