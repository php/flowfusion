--TEST--
Translated seed (inferredbugs-java: acme4j/2)
--FILE--
<?php

class DefaultConnection {
    private $conn;

    public function sendRequest($uri) {
        if ($uri === null) {
            throw new Exception("uri must not be null");
        }
        if ($this->conn !== null) {
            throw new Exception("Connection was not closed.");
        }

        $this->conn = curl_init($uri);
        curl_setopt($this->conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->conn, CURLOPT_HTTPGET, true);
        curl_exec($this->conn);
        return curl_getinfo($this->conn, CURLINFO_HTTP_CODE);
    }

    public function readJsonResponse() {
        if ($this->conn === null) {
            throw new Exception("Not connected");
        }

        $contentType = curl_getinfo($this->conn, CURLINFO_CONTENT_TYPE);
        if (!preg_match('/application\/json/', $contentType)) {
            throw new Exception("Unexpected content type: $contentType");
        }

        $response = curl_multi_getcontent($this->conn);
        return json_decode($response, true);
    }

    public function close() {
        curl_close($this->conn);
        $this->conn = null;
    }
}

$connection = new DefaultConnection();
$responseCode = $connection->sendRequest("http://example.com");
$response = $connection->readJsonResponse();
$connection->close();
--EXPECT--
