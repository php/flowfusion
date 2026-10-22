--TEST--
Translated seed (inferredbugs-csharp: Blog.Core/2)
--FILE--
<?php

class GlobalExceptionsFilter
{
    private $env;
    private $loggerHelper;

    public function __construct($env, $loggerHelper)
    {
        $this->env = $env;
        $this->loggerHelper = $loggerHelper;
    }

    public function onException($exception)
    {
        $json = new JsonErrorResponse();

        $json->message = $exception->getMessage(); 
        $errorAudit = "Unable to resolve service for";
        if (!empty($json->message) && strpos($json->message, $errorAudit) !== false) {
            $json->message = str_replace($errorAudit, "(if new services are added, the project needs recompilation) " . $errorAudit, $json->message);
        }

        if ($this->env === 'development') {
            $json->developmentMessage = $exception->getTraceAsString();
        }

        http_response_code(500);
        echo json_encode($json);

        $this->loggerHelper->logError($json->message . $this->writeLog($json->message, $exception));
    }

    private function writeLog($throwMsg, $ex)
    {
        return sprintf("\n【Custom error】: %s \n【Exception type】: %s \n【Exception message】: %s \n【Stack trace】: %s", 
                       $throwMsg, get_class($ex), $ex->getMessage(), $ex->getTraceAsString());
    }
}

class JsonErrorResponse
{
    public $message;
    public $developmentMessage;
}

class Logger
{
    public function logError($message)
    {
        // Simulate logging error (you can change this to actual logging if needed)
        file_put_contents('error.log', $message, FILE_APPEND);
    }
}

// Simulating an exception
$filter = new GlobalExceptionsFilter('development', new Logger());

try {
    throw new Exception("Unable to resolve service for XYZ");
} catch (Exception $e) {
    $filter->onException($e);
}
--EXPECT--
