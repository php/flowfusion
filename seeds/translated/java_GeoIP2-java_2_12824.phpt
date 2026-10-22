--TEST--
Translated seed (inferredbugs-java: GeoIP2-java/2)
--FILE--
<?php

class Client 
{
    private $user_id;
    private $license_key;

    public function __construct($user_id, $license_key) {
        $this->user_id = $user_id;
        $this->license_key = $license_key;
    }

    public function getCountry($ip_address) {
        $url = "https://geoip.maxmind.com/geoip/country/" . $ip_address;
        $options = [
            "http" => [
                "header" => [
                    "Accept: application/json",
                    "Authorization: Basic " . base64_encode($this->user_id . ":" . $this->license_key)
                ],
            ],
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result !== false) {
            return new Country($result);
        }
        
        return null;
    }
}

class Country 
{
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }
}

$user_id = 'your_user_id';
$license_key = 'your_license_key';
$client = new Client($user_id, $license_key);
$client->getCountry('8.8.8.8');

?>
--EXPECT--
