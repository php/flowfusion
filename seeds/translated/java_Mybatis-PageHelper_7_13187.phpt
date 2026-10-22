--TEST--
Translated seed (inferredbugs-java: Mybatis-PageHelper/7)
--FILE--
<?php
class PageInterceptor {
    protected $msCountMap = [];
    private $dialect;
    private $countSuffix = '_COUNT';
    private $additionalParametersField;

    public function __construct() {
        $this->init();
    }

    private function init() {
        // Simulate properties setup
        $this->dialect = new class {
            public function skip() { return false; }
            public function beforeCount() { return true; }
            public function afterCount($count) { return $count > 0; }
            public function beforePage() { return true; }
            public function getPageSql() { return 'SELECT * FROM data LIMIT 10'; }
            public function afterPage($resultList) { return $resultList; }
        };
        $this->additionalParametersField = 'additionalParameters';
        $this->msCountMap['count'] = 'MappedStatement';
    }

    public function intercept() {
        $parameter = []; // Simulate parameters
        $rowBounds = []; // Simulate RowBounds
        if (!$this->dialect->skip()) {
            if ($this->dialect->beforeCount()) {
                $count = 100; // Simulate count query result
                if (!$this->dialect->afterCount($count)) {
                    return [];
                }
            }
            if ($this->dialect->beforePage()) {
                $pageSql = $this->dialect->getPageSql();
                // Execute page query
                $resultList = $this->executeQuery($pageSql);
            } else {
                $resultList = []; // No pagination, return empty
            }
        } else {
            $resultList = []; // No execution
        }
        return $this->dialect->afterPage($resultList);
    }

    private function executeQuery($sql) {
        // Simulate executing a query
        return ['data1', 'data2', 'data3', 'data4', 'data5', 'data6', 'data7', 'data8', 'data9', 'data10'];
    }
}

$interceptor = new PageInterceptor();
$result = $interceptor->intercept();
print_r($result);
--EXPECT--
