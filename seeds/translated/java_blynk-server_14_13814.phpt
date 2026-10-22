--TEST--
Translated seed (inferredbugs-java: blynk-server/14)
--FILE--
<?php
class RealRedisClient {
    private $host;
    private $password;
    private $port;

    public function __construct($host, $password, $port) {
        if (empty($password)) {
            throw new Exception("Connection failed");
        }
        $this->host = $host;
        $this->password = $password;
        $this->port = $port;
    }

    public function getServerByToken($token) {
        if ($token === "test") {
            return "It's working!";
        }
        return null;
    }
}

try {
    $redisClient = new RealRedisClient("localhost", "", 6378);
    $redisClient = null; // This simulates a failure to create the client
} catch (Exception $e) {
}

$redisClient = new RealRedisClient("localhost", "123", 6378);
$result = $redisClient->getServerByToken("test");
echo $result;
?>
--EXPECT--
