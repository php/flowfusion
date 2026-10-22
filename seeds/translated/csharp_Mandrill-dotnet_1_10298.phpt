--TEST--
Translated seed (inferredbugs-csharp: Mandrill-dotnet/1)
--FILE--
<?php
class MandrillException extends Exception { public $Error; function __construct($e){$this->Error=$e; parent::__construct();} }
class MandrillSerializationException extends Exception { public $HttpResponseMessage; function __construct($c){$this->HttpResponseMessage=(object)['Content'=>$c]; parent::__construct();} }
class MandrillApi {
    private $response;
    function __construct($x='') {}
    function SetHttpClient($r){ $this->response = $r; }
    function Post($url, $payload) {
        $r = $this->response;
        $body = $r['body'];
        $status = $r['status'];
        if ($status >= 500) {
            $e = json_decode($body, true);
            throw new MandrillException((object)['Code'=>(int)$e['code'],'Message'=>$e['message'],'Name'=>$e['name'],'Status'=>$e['status']]);
        }
        $o = json_decode($body);
        if ($o === null && json_last_error() !== JSON_ERROR_NONE) throw new MandrillSerializationException($body);
        return $o;
    }
}
$api = new MandrillApi("");
$api->SetHttpClient(['status'=>200,'body'=>"{\n\t      \"Name\": \"Shawn\",\n\t      \"Id\": 1\n      }"]);
$r = $api->Post("", new stdClass());
if ($r->Name !== "Shawn" || $r->Id !== 1) exit(1);
$api->SetHttpClient(['status'=>500,'body'=>"{\n\t      \"code\": \"501\",\n\t      \"message\": \"m1\",\n\t      \"name\": \"n1\",\n\t      \"status\": \"s1\"\n      }"]);
try { $api->Post("", new stdClass()); exit(1); } catch (MandrillException $e) { if ($e->Error->Code !== 501 || $e->Error->Message !== "m1" || $e->Error->Name !== "n1" || $e->Error->Status !== "s1") exit(1); }
$api->SetHttpClient(['status'=>200,'body'=>"{\n\t    \"Name\": \"Shawn\",\n\t    \"Id\": 1\n        }"]);
$api->Post("", new stdClass());
$api->SetHttpClient(['status'=>200,'body'=>"<html></html>"]);
try { $api->Post("", new stdClass()); exit(1); } catch (MandrillSerializationException $e) { if ($e->HttpResponseMessage->Content !== "<html></html>") exit(1); }
?>
--EXPECT--
