--TEST--
Translated seed (inferredbugs-java: RuoYi/6)
--FILE--
<?php

class BaseController
{
    public function startPage($pageNum, $pageSize, $orderBy)
    {
        if (is_numeric($pageNum) && is_numeric($pageSize)) {
            $currentPage = (int)$pageNum;
            $itemsPerPage = (int)$pageSize;
            $offset = ($currentPage - 1) * $itemsPerPage;
            $data = array_slice($this->getData(), $offset, $itemsPerPage);
            $this->getDataTable($data, count($this->getData()));
        }
    }

    protected function getDataTable($list, $total)
    {
        $response = ['code' => 0, 'rows' => $list, 'total' => $total];
        echo json_encode($response);
    }

    protected function getData()
    {
        return range(1, 100); // Example data for paging
    }
}

$controller = new BaseController();
$controller->startPage(1, 10, null);
--EXPECT--
