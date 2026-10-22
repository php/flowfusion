--TEST--
Translated seed (inferredbugs-java: Resty/1)
--FILE--
<?php

class RestyAuthenticator {
    // Authentication logic would go here if necessary
}

class Resty {
    static $rath;

    static function init() {
        self::$rath = new RestyAuthenticator();
        // Setup content handlers if needed
    }

    function __construct() {
        // No options needed
    }

    function authenticate($site, $login, $pwd) {
        // Use authentication logic
    }

    function json($url) {
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}

Resty::init();
$r = new Resty();
$data = $r->json("http://ws.geonames.org/postalCodeLookupJSON?postalcode=66780&country=DE");
$name = $data['postalcodes'][0]['placeName'];
echo $name;
--EXPECT--
