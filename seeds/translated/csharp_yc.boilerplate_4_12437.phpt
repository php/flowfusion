--TEST--
Translated seed (inferredbugs-csharp: yc.boilerplate/4)
--FILE--
<?php
declare(strict_types=1);

class ApiResult {
    public bool $State;
    public function __construct(bool $state) { $this->State = $state; }
}

class RequestInfoDto {
    public string $Key = '';
    public float $StartTime = 0.0;
    public float $StopTime = 0.0;
    public int $ElapsedMilliseconds = 0;
    public bool $ResponseState = false;
}

$req = new RequestInfoDto();
$req->Key = 'abc';
$req->StartTime = microtime(true);

$payload = new ApiResult(true);
$result = json_encode($payload, JSON_THROW_ON_ERROR);
$data = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
$req->ResponseState = (bool)($data['State'] ?? false);

$req->StopTime = microtime(true);
$req->ElapsedMilliseconds = (int)(($req->StopTime - $req->StartTime) * 1000);

echo $req->ResponseState ? "1" : "0";
?>
--EXPECT--
