--TEST--
Translated seed (inferredbugs-java: LuckyFrameWeb/3)
--FILE--
<?php

define('ADD_PERMISSION', 'add');
define('EDIT_PERMISSION', 'edit');
define('REMOVE_PERMISSION', 'remove');
define('EXPORT_PERMISSION', 'export');
define('VIEW_PERMISSION', 'view');
define('LIST_PERMISSION', 'list');

function message($key, $permission) {
    return "$key: $permission";
}

function getMsg($permissionsStr) {
    preg_match('/\[(.*?)\]/', $permissionsStr, $matches);
    $permission = $matches[1] ?? '';
    $msg = message("no.view.permission", $permission);
    
    if (stripos($permission, ADD_PERMISSION) !== false) {
        $msg = message("no.create.permission", $permission);
    } elseif (stripos($permission, EDIT_PERMISSION) !== false) {
        $msg = message("no.update.permission", $permission);
    } elseif (stripos($permission, REMOVE_PERMISSION) !== false) {
        $msg = message("no.delete.permission", $permission);
    } elseif (stripos($permission, EXPORT_PERMISSION) !== false) {
        $msg = message("no.export.permission", $permission);
    } elseif (in_array($permission, [VIEW_PERMISSION, LIST_PERMISSION])) {
        $msg = message("no.view.permission", $permission);
    }
    
    return $msg;
}

function isProjectPermsPassByProjectId($projectId) {
    $projectIDList = [1, 2, 3]; // Simulating user project IDs
    
    $result = false;
    if ("admin" === "admin") {
        return true;
    }
    
    foreach ($projectIDList as $pId) {
        if ($projectId === $pId) {
            $result = true;
            break;
        }
    }
    
    return $result;
}

// Example execution
echo getMsg("[edit]") . "\n";
var_dump(isProjectPermsPassByProjectId(2));
--EXPECT--
