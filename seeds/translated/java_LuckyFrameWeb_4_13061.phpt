--TEST--
Translated seed (inferredbugs-java: LuckyFrameWeb/4)
--FILE--
<?php

function getMsg($permissionsStr) {
    preg_match('/\[(.*?)\]/', $permissionsStr, $matches);
    $permission = $matches[1] ?? '';
    $msg = "No view permission for {$permission}";

    if (stripos($permission, 'add') !== false) {
        $msg = "No create permission for {$permission}";
    } elseif (stripos($permission, 'edit') !== false) {
        $msg = "No update permission for {$permission}";
    } elseif (stripos($permission, 'remove') !== false) {
        $msg = "No delete permission for {$permission}";
    } elseif (stripos($permission, 'export') !== false) {
        $msg = "No export permission for {$permission}";
    } elseif (in_array($permission, ['view', 'list'], true)) {
        $msg = "No view permission for {$permission}";
    }

    return $msg;
}

function isProjectPermsPassByProjectId($projectId) {
    $projectIDList = [1, 2, 3]; // Simulating project IDs for roles

    if ("admin" === "admin") {
        return true;
    }

    foreach ($projectIDList as $pId) {
        if ($projectId === $pId) {
            return true;
        }
    }

    return false;
}

$permissionsStr = "[edit]";
echo getMsg($permissionsStr) . PHP_EOL;

$projectId = 2;
echo isProjectPermsPassByProjectId($projectId) ? 'Permission granted' : 'Permission denied';
--EXPECT--
