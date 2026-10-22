--TEST--
Translated seed (inferredbugs-csharp: fido2-net-lib/1)
--FILE--
<?php
$raw = '{"Challenge":"abc","HashAlgorithm":"SHA-256","Origin":"https://example.com","ClientExtensions":{},"Type":"webauthn.create"}';
$o = json_decode($raw, true);
$o["Raw"] = ["Response" => ["ClientDataJson" => $raw, "AttestationObject" => "eyJmbXQiOiJmaWRvLXUyZiIsImF0dFN0bXQiOnsieDVjIjpbIkFCQyJdLCJzaWciOiJERUYifSwiYXV0aERhdGEiOiJBQUVDSUQifQ"]];
$att = json_decode(base64_decode($o["Raw"]["Response"]["AttestationObject"]), true);
$o["AttestionObject"] = ["Fmt" => $att["fmt"], "AttStmt" => $att["attStmt"], "AuthData" => base64_decode($att["authData"])];
if ($o["Type"] !== "webauthn.create") exit;
if ($o["Challenge"] !== "abc") exit;
if ($o["Origin"] !== "https://example.com") exit;
$hashedRpId = hash("sha256", "example.com", true);
if (hash("sha256", $o["AttestionObject"]["AuthData"], true) === $hashedRpId) exit;
if ($o["AttestionObject"]["Fmt"] === "fido-u2f") {
    if (!isset($o["AttestionObject"]["AttStmt"]["x5c"], $o["AttestionObject"]["AttStmt"]["sig"])) exit;
    if (count($o["AttestionObject"]["AttStmt"]["x5c"]) !== 1) exit;
}
echo "";
?>
--EXPECT--
