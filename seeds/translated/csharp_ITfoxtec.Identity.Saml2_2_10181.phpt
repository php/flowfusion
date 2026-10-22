--TEST--
Translated seed (inferredbugs-csharp: ITfoxtec.Identity.Saml2/2)
--FILE--
<?php
$issuer = 'urn:test:issuer';
$signatureAlgorithm = 'rsa-sha256';
$certificateValidationMode = 'chain';
$revocationMode = 'online';

$saml2Configuration = [
    'Issuer' => $issuer,
    'SignatureAlgorithm' => $signatureAlgorithm,
    'CertificateValidationMode' => $certificateValidationMode,
    'RevocationMode' => $revocationMode,
    'AllowedAudienceUris' => [],
    'SignatureValidationCertificates' => [],
];

$saml2Configuration['SigningCertificate'] = [
    'PublicCertificate' => random_bytes(32),
    'KeyId' => 'key-' . bin2hex(random_bytes(8)),
    'Rsa' => ['modulus' => random_int(1, PHP_INT_MAX), 'exponent' => 65537],
];

$saml2Configuration['AllowedAudienceUris'][] = $saml2Configuration['Issuer'];

$metadata = <<<XML
<EntityDescriptor entityID="urn:test:idp">
  <IDPSSODescriptor>
    <SingleSignOnService Location="https://idp.example.com/sso"/>
    <SingleLogoutService Location="https://idp.example.com/slo"/>
    <KeyDescriptor use="signing">
      <KeyInfo><X509Data><X509Certificate>MIIB</X509Certificate></X509Data></KeyInfo>
    </KeyDescriptor>
  </IDPSSODescriptor>
</EntityDescriptor>
XML;

$xml = new SimpleXMLElement($metadata);
$saml2Configuration['AllowedIssuer'] = (string)$xml['entityID'];

$sso = $xml->IDPSSODescriptor->SingleSignOnService;
$slo = $xml->IDPSSODescriptor->SingleLogoutService;
$saml2Configuration['SingleSignOnDestination'] = (string)$sso['Location'];
$saml2Configuration['SingleLogoutDestination'] = (string)$slo['Location'];

foreach ($xml->IDPSSODescriptor->KeyDescriptor as $keyDescriptor) {
    $cert = (string)$keyDescriptor->KeyInfo->X509Data->X509Certificate;
    if ($cert !== '') {
        $saml2Configuration['SignatureValidationCertificates'][] = $cert;
    }
}

echo '';
?>
--EXPECT--
