--TEST--
Translated seed (inferredbugs-csharp: msgraph-sdk-dotnet/11)
--FILE--
<?php
class GraphRequestContext {}
class RetryOption { public $ShouldRetry; public $MaxRetry; }
class RedirectOption { public $MaxRedirects; }
class HttpRequestMessage { public $Properties = []; public $options = []; }
class BaseRequest {
    public $msg;
    function __construct($url, $client) { $this->msg = new HttpRequestMessage(); }
    function WithShouldRetry($cb) { $this->msg->Properties[(string)new GraphRequestContext()] = new GraphRequestContext(); $o = new RetryOption(); $o->ShouldRetry = $cb; $this->msg->options['retry'] = $o; }
    function WithMaxRetry($n) { $this->msg->Properties[(string)new GraphRequestContext()] = new GraphRequestContext(); $o = $this->msg->options['retry'] ?? new RetryOption(); $o->MaxRetry = $n; $this->msg->options['retry'] = $o; }
    function WithMaxRedirects($n) { $this->msg->Properties[(string)new GraphRequestContext()] = new GraphRequestContext(); $o = new RedirectOption(); $o->MaxRedirects = $n; $this->msg->options['redirect'] = $o; }
    function GetHttpRequestMessage() { return $this->msg; }
}

$requestUrl = "https://foo.bar";
$baseClient = null;
$httpResponseMessage = new stdClass();

$baseRequest = new BaseRequest($requestUrl, $baseClient);
$baseRequest->WithShouldRetry(function ($response) { return true; });
$baseRequest->GetHttpRequestMessage()->options['retry']->ShouldRetry($httpResponseMessage);

$baseRequest = new BaseRequest($requestUrl, $baseClient);
$baseRequest->WithMaxRetry(3);
$baseRequest->GetHttpRequestMessage()->options['retry']->MaxRetry;

$baseRequest = new BaseRequest($requestUrl, $baseClient);
$baseRequest->WithMaxRedirects(4);
$baseRequest->GetHttpRequestMessage()->options['redirect']->MaxRedirects;
?>
--EXPECT--
