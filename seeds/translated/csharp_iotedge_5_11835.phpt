--TEST--
Translated seed (inferredbugs-csharp: iotedge/5)
--FILE--
<?php
$entityName = "entity";
$defaultHeadOffset = 12;
$operationTimeout = 120;

$dbStoreProvider = new class {
    public array $stores = [];
    public function getDbStore(string $name) { return $this->stores[$name] ??= new stdClass(); }
    public function removeDbStore(string $name): void { unset($this->stores[$name]); }
};

$checkNonWhiteSpace = function (?string $s, string $name): string {
    if ($s === null || trim($s) === '') throw new InvalidArgumentException($name);
    return $s;
};

$getEntityStore = function (string $entityName) use ($dbStoreProvider, $checkNonWhiteSpace, $operationTimeout, $defaultHeadOffset) {
    $entityName = $checkNonWhiteSpace($entityName, 'entityName');
    $dbStore = $dbStoreProvider->getDbStore($entityName);
    return (object)[
        'dbStore' => $dbStore,
        'entityName' => $entityName,
        'timeout' => $operationTimeout,
        'offset' => $defaultHeadOffset
    ];
};

$getSequentialStore = function (string $entityName, ?int $defaultHeadOffset = null) use ($getEntityStore) {
    $underlyingStore = $getEntityStore($entityName);
    return (object)[
        'underlyingStore' => $underlyingStore,
        'entityName' => $entityName,
        'headOffset' => $defaultHeadOffset ?? 0
    ];
};

$sequentialStore = $getSequentialStore($entityName, $defaultHeadOffset);
$dbStoreProvider->removeDbStore($sequentialStore->entityName);

$entityStore = $getEntityStore($entityName);
$dbStoreProvider->removeDbStore($entityStore->entityName);
?>
--EXPECT--
