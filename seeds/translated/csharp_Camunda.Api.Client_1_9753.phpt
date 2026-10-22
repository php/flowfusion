--TEST--
Translated seed (inferredbugs-csharp: Camunda.Api.Client/1)
--FILE--
<?php

class DeploymentService {
    private $api;

    public function __construct($api) {
        $this->api = $api;
    }

    public function create($deploymentName, $duplicateFiltering = false, $changedOnly = false, $deploymentSource = null, $tenantId = null, ...$resources) {
        return $this->api->create(
            new PlainTextContent("deployment-name", $deploymentName),
            new PlainTextContent("enable-duplicate-filtering", strtolower(var_export($duplicateFiltering, true))),
            new PlainTextContent("deploy-changed-only", strtolower(var_export($changedOnly, true))),
            new PlainTextContent("deployment-source", $deploymentSource ?? "undefined"),
            $tenantId === null ? null : new PlainTextContent("tenant-id", $tenantId), 
            ...$resources
        );
    }

    public function createWithoutFilters($deploymentName, ...$resources) {
        return $this->create($deploymentName, false, false, null, null, ...$resources);
    }
}

class PlainTextContent {
    private $name;
    private $value;

    public function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }
}

class FakeApi {
    public function create(...$args) {
        // Simulate API behavior
        return "Deployment created with args: " . json_encode($args);
    }
}

$api = new FakeApi();
$service = new DeploymentService($api);
$result = $service->create("TestDeployment", true, true, "source", "tenant1", new PlainTextContent("resource1", "data1"), new PlainTextContent("resource2", "data2"));
echo $result;
--EXPECT--
