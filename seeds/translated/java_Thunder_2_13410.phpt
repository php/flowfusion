--TEST--
Translated seed (inferredbugs-java: Thunder/2)
--FILE--
<?php

class PathChildrenCacheEvent {
    public $data;
    public function __construct($path) {
        $this->data = (object) ['path' => $path];
    }
}

class ApplicationEntity {
    public $name;
    public function __construct($name) {
        $this->name = $name;
    }
}

function fromJson($json) {
    return new ApplicationEntity($json);
}

function getChildNameList() {
    return ['{"name":"app1"}', '{"name":"app2"}'];
}

$path = '/applications/app1';
$event = new PathChildrenCacheEvent($path);
$childPath = $event->data->path;
$applicationJson = substr($childPath, strrpos($childPath, '/') + 1);
$applicationEntity = fromJson($applicationJson);

$applicationJsonList = getChildNameList();
$applicationEntityList = array_map('fromJson', $applicationJsonList);

$instanceEventType = 'ONLINE';
$interfaze = 'MyInterface';
$instanceEvent = ['type' => 'ServiceInstanceEvent', 'interfaze' => $interfaze, 'entity' => $applicationEntity, 'entities' => $applicationEntityList];

echo "Watched SERVICE $instanceEventType - interface=$interfaze, application=$applicationEntity->name\n";
--EXPECT--
