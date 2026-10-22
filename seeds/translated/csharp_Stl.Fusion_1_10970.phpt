--TEST--
Translated seed (inferredbugs-csharp: Stl.Fusion/1)
--FILE--
<?php
class ExceptionFilterContext {
    public ?Throwable $exception;
    public ?array $response = null;
    public array $actionContext;
    function __construct(Throwable $e, array $ctx) {
        $this->exception = $e;
        $this->actionContext = $ctx;
    }
}

$rewriteErrors = true;
$actionExecutedContext = new ExceptionFilterContext(new RuntimeException("Error message"), ["psi" => "pub-state", "rewrite" => true]);

if ($actionExecutedContext->response !== null) {
    exit;
}

$exception = $actionExecutedContext->exception;

if ($rewriteErrors) {
    $exception = new RuntimeException($exception->getMessage() . " (rewritten)", 0, $exception);
}

error_log("Error message: " . $exception->getMessage());

$content = json_encode([
    "type" => get_class($exception),
    "message" => $exception->getMessage(),
    "code" => $exception->getCode(),
    "previous" => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$actionExecutedContext->exception = null;
$response = [
    "content" => $content,
    "statusCode" => 500,
    "headers" => [],
];

$psi = $actionExecutedContext->actionContext["psi"] ?? null;
if ($psi !== null) {
    $response["headers"]["Publication-State-Info"] = $psi;
}

$actionExecutedContext->response = $response;
echo $actionExecutedContext->response["content"];
?>
--EXPECT--
