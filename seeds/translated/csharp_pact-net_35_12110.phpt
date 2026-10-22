--TEST--
Translated seed (inferredbugs-csharp: pact-net/35)
--FILE--
<?php
class Request { public string $method; public string $path; function __construct($m, $p) { $this->method = $m; $this->path = $p; } }
class Response { public int $statusCode = 200; }
class PactProviderServiceRequest { public ?string $method = null; public ?string $path = null; }
class PactProviderServiceResponse { public int $status = 500; }
class NancyContext { public $request; public $response = null; public array $pairs = []; }
class MockRequestMapper { public $ret; function convert($r) { return $this->ret; } }
class MockRequestComparer { public $throw = false; function compare($e, $a) { if ($this->throw) throw new Exception("compare"); } }
class MockResponseMapper { public $ret; function convert($r) { return $this->ret; } }

$ctx = new NancyContext;
$ctx->request = new Request("GET", "/Test");
$expectedRequest = new PactProviderServiceRequest;
$expectedRequest->method = "GET";
$expectedRequest->path = "/Test";
$expectedResponse = new PactProviderServiceResponse;
$expectedResponse->status = 200;
$actualRequest = new PactProviderServiceRequest;
$actualRequest->method = "PUT";
$actualRequest->path = "/Test";
$ctx->pairs = [[$expectedRequest, $expectedResponse]];

$requestMapper = new MockRequestMapper;
$requestMapper->ret = $actualRequest;
$requestComparer = new MockRequestComparer;
$requestComparer->throw = true;
$responseMapper = new MockResponseMapper;
$responseMapper->ret = new Response;
$responseMapper->ret->statusCode = 500;

try {
    $mapped = $requestMapper->convert($ctx->request);
    foreach ($ctx->pairs as [$exp, $resp]) {
        try {
            $requestComparer->compare($exp, $mapped);
            $ctx->response = $responseMapper->convert($resp);
        } catch (Throwable $e) {
            $ctx->response = $responseMapper->convert(new PactProviderServiceResponse);
        }
    }
} catch (Throwable $e) {
    $ctx->response = $responseMapper->convert(new PactProviderServiceResponse);
}

echo $ctx->response->statusCode;
?>
--EXPECT--
