--TEST--
Translated seed (inferredbugs-csharp: atata/4)
--FILE--
<?php
declare(strict_types=1);

$baseUrl = "http://example.com";
if ($baseUrl !== null && filter_var($baseUrl, FILTER_VALIDATE_URL) === false) {
    throw new InvalidArgumentException("Invalid URL format \"$baseUrl\".");
}

$current = new stdClass();
$current->TemporarilyPreservedPageObjectList = [];
$current->Log = new class {
    public function Start(string $s): void {}
    public function EndSection(): void {}
    public function Info(string $s): void {}
    public function InfoWithExecutionTimeInBrackets(string $s, float $t): void {}
    public function InfoWithExecutionTime(string $s, float $t): void {}
};
$current->BaseUrl = $baseUrl;
$current->SetUpDateTime = microtime(true);

$testName = "bug pattern";
$msg = "Starting test";
if (trim($testName) !== '') {
    $msg .= ": " . $testName;
}
$current->Log->Info($msg);

$current->Log->Start("Init WebDriver");
$current->Driver = new class {
    public function Quit(): void {}
};
$current->Log->EndSection();

$current->CleanExecutionStartDateTime = microtime(true);

$cleanTestExecutionTime = microtime(true) - $current->CleanExecutionStartDateTime;
$current->Log->Start("Clean-up test context");
$current->Driver->Quit();
$current->TemporarilyPreservedPageObjectList = [];
$current->Log->EndSection();

$testExecutionTime = microtime(true) - $current->SetUpDateTime;
$current->Log->InfoWithExecutionTimeInBrackets("Finished test", $testExecutionTime);
$current->Log->InfoWithExecutionTime("Сlean test execution time: ", $cleanTestExecutionTime);

$current = null;
?>
--EXPECT--
