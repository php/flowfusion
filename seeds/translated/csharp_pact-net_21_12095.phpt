--TEST--
Translated seed (inferredbugs-csharp: pact-net/21)
--FILE--
<?php
class PactProviderServiceRequest { public $Method; public $Path; }
class PactProviderServiceResponse { public $Status = 0; }
class Response { public $StatusCode; }
class Request { public $Method; public $Path; public $Protocol; function __construct($m, $p, $proto) { $this->Method = $m; $this->Path = $p; $this->Protocol = $proto; } }
class NancyContext { public $Request; public $Parameters; public $Response; }
class CompareFailedException extends Exception {}

class MockRequestMapper {
    public $ret;
    function Convert($request) { return $this->ret; }
}
class MockRequestComparer {
    public $throw = false;
    function Compare($expected, $actual) { if ($this->throw) throw new CompareFailedException(); }
}
class MockResponseMapper {
    public $ret;
    function Convert($response) { return $this->ret; }
}

$ctx = new NancyContext();
$ctx->Request = new Request("GET", "/Test", "HTTP");
$ctx->Parameters = new stdClass();
$ctx->Parameters->ExpectedRequest = new PactProviderServiceRequest();
$ctx->Parameters->ExpectedRequest->Method = "GET";
$ctx->Parameters->ExpectedRequest->Path = "/Test";
$ctx->Parameters->ExpectedResponse = new PactProviderServiceResponse();
$ctx->Parameters->ExpectedResponse->Status = 200;

$reqMapper = new MockRequestMapper();
$reqComparer = new MockRequestComparer();
$respMapper = new MockResponseMapper();

$reqMapper->ret = new PactProviderServiceRequest();
$reqMapper->ret->Method = "PUT";
$reqMapper->ret->Path = "/Test";
$reqComparer->throw = true;
$respMapper->ret = new Response();
$respMapper->ret->StatusCode = 200;

try {
    $actual = $reqMapper->Convert($ctx->Request);
    $reqComparer->Compare($ctx->Parameters->ExpectedRequest, $actual);
    $ctx->Response = $respMapper->Convert($ctx->Parameters->ExpectedResponse);
} catch (Throwable $e) {
    $bad = new PactProviderServiceResponse();
    $bad->Status = 500;
    $ctx->Response = $respMapper->Convert($bad);
}

echo $ctx->Response->StatusCode === 200 ? "" : "";
?>
--EXPECT--
