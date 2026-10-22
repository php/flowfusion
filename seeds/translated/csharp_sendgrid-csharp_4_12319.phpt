--TEST--
Translated seed (inferredbugs-csharp: sendgrid-csharp/4)
--FILE--
<?php
$reText = '/<\%\s*\%>/';
$reHtml = '/<\%\s*[^\s]+\s*\%>/';
$header = ['enabled' => [], 'settings' => []];
$enable = function (&$header, $filter) {
    $header['enabled'][$filter] = true;
};
$add = function (&$header, $filter, $key, $value) {
    $header['settings'][$filter][$key] = $value;
};
$filters = [
    'Gravatar' => 'gravatar',
    'OpenTracking' => 'opentrack',
    'ClickTracking' => 'clicktrack',
    'SpamCheck' => 'spamcheck',
    'Unsubscribe' => 'subscriptiontrack',
    'Footer' => 'footer',
    'GoogleAnalytics' => 'ganalytics',
    'Template' => 'template',
    'Bcc' => 'bcc',
    'BypassListManagement' => 'bypass_list_management'
];
$enable($header, $filters['ClickTracking']);
$add($header, $filters['ClickTracking'], 'text', null);
$enable($header, $filters['SpamCheck']);
$add($header, $filters['SpamCheck'], 'score', (string)5);
$add($header, $filters['SpamCheck'], 'url', null);
$text = 'Hello <% %> world';
$html = 'Hello <%name%> world';
if (!preg_match($reText, $text)) throw new Exception('Missing substitution tag in text');
if (!preg_match($reHtml, $html)) throw new Exception('Missing substitution tag in html');
$enable($header, $filters['Unsubscribe']);
$add($header, $filters['Unsubscribe'], 'text', $text);
$add($header, $filters['Unsubscribe'], 'html', $html);
$add($header, $filters['Unsubscribe'], 'replace', 'X');
$add($header, $filters['Unsubscribe'], 'landing', 'Y');
$enable($header, $filters['Template']);
$add($header, $filters['Template'], 'html', $html);
$enable($header, $filters['Bcc']);
$add($header, $filters['Bcc'], 'email', 'bcc@example.com');
$json = json_encode($header, JSON_UNESCAPED_SLASHES);
if ($json !== false && $json !== '') {
    $smtpapi = '{' . $json . '}';
}
echo "";
?>
--EXPECT--
