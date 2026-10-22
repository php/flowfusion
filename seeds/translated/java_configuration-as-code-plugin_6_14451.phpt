--TEST--
Translated seed (inferredbugs-java: configuration-as-code-plugin/6)
--FILE--
<?php

class GroupPermissionDefinition {
    public function grantPermission(&$grantedPermissions) {
        // Simulate a permission granting process
        $permission = 'permission_' . rand(1, 5);
        $grantedPermissions[$permission][] = 'user_' . rand(1, 10);
    }
}

class GlobalMatrixAuthorizationStrategy {
    public $grantedPermissions;
}

$config = [
    'grantedPermissions' => [new GroupPermissionDefinition(), new GroupPermissionDefinition(), new GroupPermissionDefinition()]
];

$permissions = $config['grantedPermissions'];
$grantedPermissions = [];

foreach ($permissions as $entry) {
    $entry->grantPermission($grantedPermissions);
}

// Reflection to set the grantedPermissions property
$gms = new GlobalMatrixAuthorizationStrategy();
$reflection = new ReflectionClass($gms);
$property = $reflection->getProperty('grantedPermissions');
$property->setAccessible(true);
$property->setValue($gms, $grantedPermissions);
--EXPECT--
