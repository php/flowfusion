--TEST--
Translated seed (inferredbugs-java: MicroCommunity/3)
--FILE--
<?php

class QueryStaffByUserNameServiceListener {
    public function service($data) {
        if (!isset($data['page'])) {
            return;
        }
        if (!isset($data['rows'])) {
            return;
        }
        if (!isset($data['storeId'])) {
            return;
        }
        if (!isset($data['name'])) {
            return;
        }

        $responseEntity = $this->callService($data);
        
        if ($responseEntity['statusCode'] !== 200) {
            return;
        }

        $resultInfo = json_decode($responseEntity['body'], true)['users'];

        if ($resultInfo === null || count($resultInfo) < 1) {
            echo json_encode([]);
            return;
        }
    }

    private function callService($data) {
        // Simulate a response from a service
        return ['statusCode' => 200, 'body' => json_encode(['users' => []])];
    }
}

$listener = new QueryStaffByUserNameServiceListener();
$data = ['page' => 1, 'rows' => 10, 'storeId' => '123', 'name' => 'John Doe'];
$listener->service($data);
--EXPECT--
