--TEST--
Translated seed (inferredbugs-java: Mybatis-PageHelper/2)
--FILE--
<?php
class SqlUtil {
    private static $localPage;

    public static function setLocalPage($page) {
        self::$localPage = $page;
    }

    public static function getLocalPage() {
        return self::$localPage;
    }
}

class Page {
    private $pageNum;
    private $pageSize;
    private $total;
    private $orderBy;

    public function __construct($pageNum, $pageSize) {
        $this->pageNum = $pageNum;
        $this->pageSize = $pageSize;
        $this->total = 0;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
    }

    public function isOrderByOnly() {
        return !isset($this->pageSize) || $this->pageSize <= 0;
    }
}

function startPage($pageNum, $pageSize) {
    $page = new Page($pageNum, $pageSize);
    SqlUtil::setLocalPage($page);
    return $page;
}

$page = startPage(1, 0);
$localPage = SqlUtil::getLocalPage();
$localPage->total = 100; // Simulating count retrieval for testing
echo $localPage->getTotal();
--EXPECT--
