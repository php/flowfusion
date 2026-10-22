--TEST--
Translated seed (inferredbugs-java: MicroCommunity/9)
--FILE--
<?php

class ResourceEnterToDepartmentListener {
    private $auditUserInnerServiceSMOImpl;

    public function __construct() {
        // Simulating Dependency Injection
        $this->auditUserInnerServiceSMOImpl = new stdClass();
        $this->auditUserInnerServiceSMOImpl->queryAuditUsers = function($auditUserDto) {
            return [
                (object)['userId' => 'user1', 'userName' => 'Alice', 'storeId' => $auditUserDto->storeId],
                (object)['userId' => 'user2', 'userName' => 'Bob', 'storeId' => $auditUserDto->storeId]
            ];
        };
    }

    public function notify($delegateTask) {
        echo "查询部门审核人员\n";

        $purchaseApplyDto = $delegateTask->purchaseApplyDto;
        $auditUserDto = new stdClass();
        $auditUserDto->storeId = $purchaseApplyDto->storeId;
        $auditUserDto->objCode = "resourceEntry";
        $auditUserDto->auditLink = "809001";
        
        $auditUserDtos = ($this->auditUserInnerServiceSMOImpl->queryAuditUsers)($auditUserDto);

        foreach ($auditUserDtos as $tmpAuditUser) {
            echo "查询到用户：" . $tmpAuditUser->userName . "\n";
            $delegateTask->{$tmpAuditUser->userId} = $tmpAuditUser;
        }

        echo "查询审核人员人数：" . count($auditUserDtos) . "\n";

        if (empty($auditUserDtos)) {
            return;
        }

        $delegateTask->assignee = $auditUserDtos[0]->userId;
        echo "设置部门审核人员：" . $auditUserDtos[0]->userId . $auditUserDtos[0]->userName . "\n";
    }
}

$listener = new ResourceEnterToDepartmentListener();
$delegateTask = new stdClass();
$delegateTask->purchaseApplyDto = (object)['storeId' => 'store1'];
$listener->notify($delegateTask);
?>
--EXPECT--
