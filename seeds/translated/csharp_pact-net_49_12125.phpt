--TEST--
Translated seed (inferredbugs-csharp: pact-net/49)
--FILE--
<?php
class Request {
    public string $method;
    public string $path;
    public string $protocol;
    function __construct(string $method, string $path, string $protocol) {
        $this->method = $method;
        $this->path = $path;
        $this->protocol = $protocol;
    }
}
class Response {
    public int $statusCode = 0;
    public string $reasonPhrase = '';
}
class ProviderServiceRequest {
    public string $method = '';
    public string $path = '';
}
class ProviderServiceResponse {
    public int $status = 0;
    public string $body = '';
}
class ProviderServiceInteraction {
    public ?ProviderServiceRequest $request = null;
    public ?ProviderServiceResponse $response = null;
}
class NancyContext {
    public Request $request;
    public array $interactions = [];
}
class CompareFailedException extends Exception {}
$mockRequestMapper = new class {
    public bool $throw = false;
    public ProviderServiceRequest $returnValue;
    function convert(Request $r): ProviderServiceRequest {
        if ($this->throw) throw new CompareFailedException("Something\r\n \t \\ failed");
        return $this->returnValue;
    }
};
$mockResponseMapper = new class {
    function convert(ProviderServiceResponse $r): Response {
        $x = new Response();
        $x->statusCode = 500;
        return $x;
    }
};
$ctx = new NancyContext();
$ctx->request = new Request("GET", "/Test", "HTTP");
$mockRequestMapper->throw = true;
try {
    $mockRequestMapper->convert($ctx->request);
} catch (CompareFailedException $e) {
    $resp = new ProviderServiceResponse();
    $resp->status = 500;
    $resp->body = preg_replace("/[\r\n\t\\\\]+/", " ", $e->getMessage());
    $resp->body = preg_replace("/ +/", " ", $resp->body);
    $resp->body = trim($resp->body);
    $out = $mockResponseMapper->convert($resp);
    $out->reasonPhrase = $resp->body;
    echo $out->reasonPhrase, "\n";
}
?>
--EXPECT--
