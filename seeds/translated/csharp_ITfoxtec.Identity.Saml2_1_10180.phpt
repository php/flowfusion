--TEST--
Translated seed (inferredbugs-csharp: ITfoxtec.Identity.Saml2/1)
--FILE--
<?php
$issuer = "issuer";
$signatureAlgorithm = "rsa-sha256";
$certificateValidationMode = "chain";
$revocationMode = "online";

$allowedAudienceUris = [];
$allowedAudienceUris[] = $issuer;

$metadata = '<?xml version="1.0"?>
<EntityDescriptor entityID="https://idp.example.com">
  <IDPSSODescriptor>
    <SingleSignOnService Location="https://idp.example.com/sso"/>
    <SingleLogoutService Location="https://idp.example.com/slo"/>
    <KeyDescriptor use="signing">
      <KeyInfo xmlns="http://www.w3.org/2000/09/xmldsig#">
        <X509Data>
          <X509Certificate>MIIB</X509Certificate>
        </X509Data>
      </KeyInfo>
    </KeyDescriptor>
  </IDPSSODescriptor>
</EntityDescriptor>';

$xml = simplexml_load_string($metadata);
if ($xml === false) {
    throw new Exception("metadata parse failed");
}

$entityId = (string)$xml['entityID'];
$idp = $xml->IDPSSODescriptor;
if ($idp === null) {
    throw new Exception("IdPSsoDescriptor not loaded from metadata.");
}

$singleSignOnDestination = (string)$idp->SingleSignOnService[0]['Location'];
$singleLogoutDestination = (string)$idp->SingleLogoutService[0]['Location'];

$signatureValidationCertificates = [];
foreach ($idp->KeyDescriptor as $keyDescriptor) {
    $signatureValidationCertificates[] = (string)$keyDescriptor->KeyInfo->children('http://www.w3.org/2000/09/xmldsig#')->X509Data->X509Certificate;
}

$saml2Configuration = [
    'Issuer' => $issuer,
    'SignatureAlgorithm' => $signatureAlgorithm,
    'CertificateValidationMode' => $certificateValidationMode,
    'RevocationMode' => $revocationMode,
    'SigningCertificate' => [
        'Cer' => "dummy-cert",
        'KeyIdentifier' => "dummy-key",
    ],
    'AllowedAudienceUris' => $allowedAudienceUris,
    'AllowedIssuer' => $entityId,
    'SingleSignOnDestination' => $singleSignOnDestination,
    'SingleLogoutDestination' => $singleLogoutDestination,
    'SignatureValidationCertificates' => $signatureValidationCertificates,
];

echo $saml2Configuration['Issuer'];
?>
--EXPECT--
