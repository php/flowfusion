--TEST--
Translated seed (inferredbugs-java: dataverse/18)
--FILE--
<?php
class DepositReceipt {}
class DataverseUser {
    public function getUserName() { return 'user'; }
}
class Dataset {
    public function getOwner() { return new Dataverse(); }
    public function getIdentifier() { return 'datasetIdentifier'; }
}
class Dataverse {}
class UrlManager {
    private $type = 'study';
    public function processUrl($uri) {}
    public function getTargetType() { return $this->type; }
    public function getTargetIdentifier() { return 'globalId'; }
    public function getHostnamePlusBaseUrlPath($uri) { return 'http://localhost'; }
}
class SwordAuth {
    public function auth($cred) { return new DataverseUser(); }
    public function hasAccessToModifyDataverse($user, $dataverse) { return true; }
}
class DatasetService {
    public function find($id) { return new Dataset(); }
}
class ReceiptGenerator {
    public function createReceipt($baseUrl, $dataset) { return new DepositReceipt(); }
}
class SwordError extends Exception {}
class SwordServerException extends Exception {}

$uri = "http://example.com";
$authCredentials = [];
$datasetService = new DatasetService();
$swordAuth = new SwordAuth();
$urlManager = new UrlManager();
$receiptGenerator = new ReceiptGenerator();

$user = $swordAuth->auth($authCredentials);
$urlManager->processUrl($uri);
$type = $urlManager->getTargetType();

if ($type === 'study') {
    $globalId = $urlManager->getTargetIdentifier();
    $dataset = $datasetService->find(10);
    if ($dataset) {
        $dv = $dataset->getOwner();
        if ($swordAuth->hasAccessToModifyDataverse($user, $dv)) {
            $baseUrl = $urlManager->getHostnamePlusBaseUrlPath($uri);
            $depositReceipt = $receiptGenerator->createReceipt($baseUrl, $dataset);
        }
    }
}
--EXPECT--
