--TEST--
Translated seed (inferredbugs-csharp: DotNetCore.SKIT.FlurlHttpClient.Wechat/1)
--FILE--
<?php

function convertPkcs8PrivateKeyToByteArray($privateKey)
{
    $privateKey = str_replace("-----BEGIN PRIVATE KEY-----", "", $privateKey);
    $privateKey = str_replace("-----END PRIVATE KEY-----", "", $privateKey);
    $privateKey = preg_replace("/\\s+/", "", $privateKey);
    return base64_decode($privateKey);
}

function convertPkcs8PublicKeyToByteArray($publicKey)
{
    $publicKey = str_replace("-----BEGIN PUBLIC KEY-----", "", $publicKey);
    $publicKey = str_replace("-----END PUBLIC KEY-----", "", $publicKey);
    $publicKey = preg_replace("/\\s+/", "", $publicKey);
    return base64_decode($publicKey);
}

function signWithSHA256($privateKey, $plainText)
{
    $privateKeyBytes = convertPkcs8PrivateKeyToByteArray($privateKey);
    $plainBytes = $plainText;
    openssl_sign($plainBytes, $signature, $privateKeyBytes, OPENSSL_ALGO_SHA256);
    return base64_encode($signature);
}

function verifyWithSHA256($publicKey, $plainText, $signature)
{
    $publicKeyBytes = convertPkcs8PublicKeyToByteArray($publicKey);
    $signatureBytes = base64_decode($signature);
    return openssl_verify($plainText, $signatureBytes, $publicKeyBytes, OPENSSL_ALGO_SHA256) === 1;
}

function main()
{
    $privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqh...";
    $publicKey = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0...";
    $data = "This is a test message.";

    $signature = signWithSHA256($privateKey, $data);
    $isValid = verifyWithSHA256($publicKey, $data, $signature);

    if ($isValid) {
        echo "Signature is valid.";
    } else {
        echo "Signature is invalid.";
    }
}

main();

?>
--EXPECT--
