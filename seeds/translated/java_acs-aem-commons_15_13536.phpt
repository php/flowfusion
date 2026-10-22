--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/15)
--FILE--
<?php

$cookieName = "testCookie";
$cookieValue = "This is a test value";
$cookiePath = "/";
$cookieMaxAge = 600; // 10 minutes

setcookie($cookieName, $cookieValue, time() + $cookieMaxAge, $cookiePath);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_COOKIE[$cookieName])) {
        $value = $_COOKIE[$cookieName];
        setcookie($cookieName, "", time() - 3600, $cookiePath); // Remove cookie
        echo "Cookie value: " . $value;
    }
} else {
    echo "Not a GET request";
}

?>
--EXPECT--
