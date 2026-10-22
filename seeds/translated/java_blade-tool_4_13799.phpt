--TEST--
Translated seed (inferredbugs-java: blade-tool/4)
--FILE--
<?php

class BaseEntity {
    public $createUser;
    public $createTime;
    public $updateUser;
    public $updateTime;
    public $status;
    public $isDeleted;
}

class SecureUtil {
    public static function getUser() {
        return (object) ['userId' => 1];
    }
}

class BladeConstant {
    const DB_STATUS_NORMAL = 1;
    const DB_NOT_DELETED = 0;
}

class BaseServiceImpl {
    private $modelClass;

    public function __construct() {
        $this->modelClass = BaseEntity::class;
    }

    public function save($entity) {
        $user = SecureUtil::getUser();
        $now = date('Y-m-d H:i:s');
        $entity->createUser = $user->userId;
        $entity->createTime = $now;
        $entity->updateUser = $user->userId;
        $entity->updateTime = $now;
        $entity->status = BladeConstant::DB_STATUS_NORMAL;
        $entity->isDeleted = BladeConstant::DB_NOT_DELETED;
        return true; // Simulating save operation
    }

    public function updateById($entity) {
        $user = SecureUtil::getUser();
        $entity->updateUser = $user->userId;
        $entity->updateTime = date('Y-m-d H:i:s');
        return true; // Simulating update operation
    }

    public function deleteLogic($ids) {
        $user = SecureUtil::getUser();
        $entity = new $this->modelClass;
        $entity->updateUser = $user->userId;
        $entity->updateTime = date('Y-m-d H:i:s');
        return true; // Simulating delete operation
    }
}

$service = new BaseServiceImpl();
$entity = new BaseEntity();
$service->save($entity);
$service->updateById($entity);
$service->deleteLogic([1, 2, 3]);
--EXPECT--
