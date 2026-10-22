--TEST--
Translated seed (inferredbugs-java: configuration-as-code-plugin/7)
--FILE--
<?php

class ProjectMatrixAuthorizationStrategy {
    private $permissions = [];

    public function add($permission, $sid) {
        if (!isset($this->permissions[$permission])) {
            $this->permissions[$permission] = [];
        }
        $this->permissions[$permission][] = $sid;
    }
}

class GroupPermissionDefinition {
    private $permission;

    public function __construct($permission) {
        $this->permission = $permission;
    }

    public function grantPermission(&$grantedPermissions) {
        if (!isset($grantedPermissions[$this->permission])) {
            $grantedPermissions[$this->permission] = [];
        }
        $grantedPermissions[$this->permission][] = "someSid"; // Simulating SID
    }
}

$config = [
    "grantedPermissions" => [
        new GroupPermissionDefinition("read"),
        new GroupPermissionDefinition("write"),
        new GroupPermissionDefinition("delete")
    ]
];

$grantedPermissions = [];
foreach ($config["grantedPermissions"] as $entry) {
    $entry->grantPermission($grantedPermissions);
}

$gms = new ProjectMatrixAuthorizationStrategy();
foreach ($grantedPermissions as $permission => $sids) {
    foreach ($sids as $sid) {
        $gms->add($permission, $sid);
    }
}
--EXPECT--
