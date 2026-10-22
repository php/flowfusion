--TEST--
Translated seed (inferredbugs-java: RuoYi/7)
--FILE--
<?php

class BaseController
{
    protected function startPage()
    {
        $pageNum = filter_input(INPUT_GET, 'pageNum', FILTER_VALIDATE_INT);
        $pageSize = filter_input(INPUT_GET, 'pageSize', FILTER_VALIDATE_INT);
        if ($pageNum !== false && $pageSize !== false) {
            $data = range(1, 100); // Simulate a data set
            $offset = ($pageNum - 1) * $pageSize;
            $pagedData = array_slice($data, $offset, $pageSize);
            $this->getDataTable($pagedData);
        }
    }

    protected function getDataTable($list)
    {
        $total = 100; // Total items
        $rspData = [
            'code' => 0,
            'rows' => $list,
            'total' => $total,
        ];
        echo json_encode($rspData);
    }
}

$controller = new BaseController();
$controller->startPage();
--EXPECT--
