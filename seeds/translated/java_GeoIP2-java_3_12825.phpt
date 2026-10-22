--TEST--
Translated seed (inferredbugs-java: GeoIP2-java/3)
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

    public function Country($ip_address) {
        $url = "https://geoip.maxmind.com/geoip/country/" . $ip_address;
        $context = stream_context_create([
            "http" => [
                "header" => [
                    "Accept: application/json",
                    "Authorization: Basic " . base64_encode($this->user_id . ":" . $this->license_key)
                ]
            ]
        ]);

        $response = @file_get_contents($url, false, $context);
        if ($response !== false) {
            return new Country($response);
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

$client = new Client("your_user_id", "your_license_key");
$country = $client->Country("8.8.8.8");
--EXPECT--
