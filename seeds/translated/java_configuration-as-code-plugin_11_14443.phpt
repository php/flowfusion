--TEST--
Translated seed (inferredbugs-java: configuration-as-code-plugin/11)
--FILE--
<?php

class Role {
}

class RoleMap {
    private $roles;

    public function __construct($roles) {
        $this->roles = $roles;
    }
}

class RoleBasedAuthorizationStrategy {
    const GLOBAL = 'global';
    const PROJECT = 'items';
    const SLAVE = 'agents';

    private $grantedRoles;

    public function __construct($grantedRoles) {
        $this->grantedRoles = $grantedRoles;
    }
}

class ConfiguratorException extends Exception {
}

class CNode {
    private $mapping;

    public function __construct($mapping) {
        $this->mapping = $mapping;
    }

    public function asMapping() {
        return $this->mapping;
    }
}

class Mapping {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function get($key) {
        return $this->data[$key] ?? null;
    }
}

class Sequence {
    private $items;

    public function __construct($items) {
        $this->items = $items;
    }

    public function getItems() {
        return $this->items;
    }
}

function retrieveRoleMap($config, $name) {
    $map = $config->asMapping();
    $c = $map->get($name);

    $resMap = [];
    if ($c === null) {
        return new RoleMap($resMap);
    }

    foreach ($c->getItems() as $entry) {
        $definition = new Role(); // Simulating the RoleDefinition
        $resMap[$definition] = []; // Simulate assignments
    }

    return new RoleMap($resMap);
}

$config = new CNode(new Mapping([
    'roles' => new Sequence([
        ['role' => 'admin'],
        ['role' => 'user']
    ])
]));

$grantedRoles = [];
$rolesConfig = $config->asMapping()->get('roles');

if ($rolesConfig !== null) {
    $grantedRoles[RoleBasedAuthorizationStrategy::GLOBAL] = retrieveRoleMap($rolesConfig, 'global');
    $grantedRoles[RoleBasedAuthorizationStrategy::PROJECT] = retrieveRoleMap($rolesConfig, 'items');
    $grantedRoles[RoleBasedAuthorizationStrategy::SLAVE] = retrieveRoleMap($rolesConfig, 'agents');
}

$strategy = new RoleBasedAuthorizationStrategy($grantedRoles);
--EXPECT--
