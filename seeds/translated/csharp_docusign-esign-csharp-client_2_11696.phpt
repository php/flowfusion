--TEST--
Translated seed (inferredbugs-csharp: docusign-esign-csharp-client/2)
--FILE--
<?php
$basePath = 'https://www.docusign.net/restapi';
function urlEncodeLong($input) {
    if ($input === null) throw new InvalidArgumentException('input');
    $maxLength = 32766;
    if (strlen($input) <= $maxLength) return rawurlencode($input);
    $out = '';
    for ($i = 0, $n = strlen($input); $i < $n; $i += $maxLength) $out .= rawurlencode(substr($input, $i, min($maxLength, $n - $i)));
    return $out;
}
function sanitizeFilename($filename) {
    return preg_match('~.*[/\\\\](.*)$~', $filename, $m) ? $m[1] : $filename;
}
function buildQueryString($clientId, $scopes, $redirectUri, $responseType, $state) {
    $q = '';
    if ($responseType !== null && $responseType !== '') $q .= 'response_type=' . $responseType;
    if ($scopes !== null && $scopes !== '') $q .= '&scope=' . $scopes;
    if ($clientId !== null && $clientId !== '') $q .= '&client_id=' . $clientId;
    if ($redirectUri !== null && $redirectUri !== '') $q .= '&redirect_uri=' . $redirectUri;
    if ($state !== null && $state !== '') $q .= '&state=' . $state;
    return $q;
}
function getOAuthBasePath($basePath) {
    return ($basePath === null || str_starts_with($basePath, 'https://demo') || str_starts_with($basePath, 'http://demo')) ? 'account-d.docusign.com' : 'account.docusign.com';
}
$long = str_repeat('a', 32767) . ' b/c?d#e';
echo urlEncodeLong($long), "\n";
echo sanitizeFilename("C:\\temp\\subdir/file.txt"), "\n";
echo buildQueryString('client', 'scope1%20scope2', 'https://example.com/cb', 'code', 'state'), "\n";
echo getOAuthBasePath($basePath), "\n";
$fp = fopen('php://temp', 'r+');
fwrite($fp, "hello");
rewind($fp);
echo stream_get_contents($fp), "\n";
$bytes = base64_encode('DocuSign');
echo $bytes, "\n";
?>
--EXPECT--
