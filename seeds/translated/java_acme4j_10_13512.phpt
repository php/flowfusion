--TEST--
Translated seed (inferredbugs-java: acme4j/10)
--FILE--
<?php

$resourceUrl = "http://example.com/acme/resource";
$locationUrl = "http://example.com/acme/registration";
$agreementUri = "http://example.com/agreement.pdf";

class TestableConnectionProvider {
    private $jsonResponse;
    private $response;

    public function sendSignedRequest($url, $claims, $session) {
        if ($url !== $GLOBALS['locationUrl']) {
            throw new Exception("Unexpected URL");
        }
        $this->jsonResponse = json_decode('{"status":"valid","termsOfServiceAgreed":true,"contacts":["mailto:foo2@example.com"]}');
        $this->response = 202; // HTTP_ACCEPTED
    }

    public function sendRequest($url, $session) {
        if ($url === "https://example.com/acme/acct/1/orders") {
            $this->jsonResponse = json_encode(["orders" => ["https://example.com/acme/order/1"]]);
            $this->response = 200; // HTTP_OK
        } else {
            $this->response = 404; // HTTP_NOT_FOUND
        }
    }

    public function accept(...$httpStatus) {
        if (!in_array($this->response, $httpStatus)) {
            throw new Exception("Unexpected HTTP status");
        }
        return $this->response;
    }

    public function readJsonResponse() {
        return $this->jsonResponse;
    }

    public function getLocation() {
        return $GLOBALS['locationUrl'];
    }
}

session_start();
$provider = new TestableConnectionProvider();
$session = $provider->createSession();
$provider->sendSignedRequest($GLOBALS['locationUrl'], json_encode([]), $session);
$registration = json_decode($provider->readJsonResponse());

if ($registration) {
    echo $registration->status; // Should be "valid"
    echo "\n";
    echo json_encode($registration->contacts); // Should contain "mailto:foo2@example.com"
}

$provider->sendRequest("https://example.com/acme/acct/1/orders", $session);
$provider->accept(200, 404); // Validate the HTTP status

$provider->sendRequest($GLOBALS['resourceUrl'], $session);
$provider->accept(201); // Validate the HTTP status
$provider->close();

?>
--EXPECT--
