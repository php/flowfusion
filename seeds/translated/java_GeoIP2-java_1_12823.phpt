--TEST--
Translated seed (inferredbugs-java: GeoIP2-java/1)
--FILE--
<?php

class Client {
    private $user_id;
    private $license_key;

    public function __construct($user_id, $license_key) {
        $this->user_id = $user_id;
        $this->license_key = $license_key;
    }

    public function Country($ip_address) {
        // Simulating a response similar to the Java version's JSONObject
        return json_decode('{"country": {"name": {"en": "United States"}}}');
    }
}

function main($argv) {
    if ($argc < 4) {
        return;
    }
    
    $user_id = $argv[1];
    $license_key = $argv[2];
    $ip_address = $argv[3];
    
    $cl = new Client($user_id, $license_key);
    $o = $cl->Country($ip_address);
    
    $name = $o->country->name->en;
    echo $name;
}

main($argv);

?>
--EXPECT--
