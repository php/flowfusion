--TEST--
Translated seed (inferredbugs-csharp: MollieApi/1)
--FILE--
<?php
$clientId = 'client_id';
$state = 'abcde';
$scopes = ['payments.read', 'payments.write'];

foreach ([['payments.read'], $scopes] as $scopeList) {
    $url = 'https://www.mollie.com/oauth2/authorize?client_id=' . rawurlencode($clientId) . '&state=' . rawurlencode($state) . '&scope=' . implode('+', array_map('rawurlencode', $scopeList)) . '&response_type=code&approval_prompt=auto';
    echo $url, PHP_EOL;
}
?>
--EXPECT--
