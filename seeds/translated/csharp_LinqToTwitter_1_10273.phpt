--TEST--
Translated seed (inferredbugs-csharp: LinqToTwitter/1)
--FILE--
<?php
class Credentials {
    public $OAuthToken = '';
    public $AccessToken = '';
    public $ScreenName = '';
    public $UserId = '';
    public $UserName = '';
    public $Password = '';
    function Save() {}
}

$credentials = new Credentials();
$credentials->UserName = "user";
$credentials->Password = "pass";

$postData = [];
$postData["x_auth_username"] = $credentials->UserName;
$postData["x_auth_password"] = $credentials->Password;
$postData["x_auth_mode"] = "client_auth";

$screenName = null;
$userID = null;

if ($screenName !== null) {
    $credentials->ScreenName = $screenName;
    $credentials->UserId = $userID;
}

$credentials->OAuthToken = "token";
$credentials->AccessToken = "secret";
$credentials->ScreenName = $screenName;
$credentials->UserId = $userID;
$credentials->Save();

echo $postData["x_auth_username"] . "\n";
echo $postData["x_auth_password"] . "\n";
echo $postData["x_auth_mode"] . "\n";
?>
--EXPECT--
