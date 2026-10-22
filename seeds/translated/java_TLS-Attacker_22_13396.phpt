--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/22)
--FILE--
<?php

class CertificateFetcher {
    public static function fetchServerCertificate($connect, $cipherSuites) {
        $config = new stdClass();
        $config->host = $connect;
        $config->supportedCiphersuites = $cipherSuites;

        $context = (object)[
            'selectedProtocolVersion' => 'TLS1.2',
            'selectedCipherSuite' => $cipherSuites[0],
            'workflowTrace' => []
        ];

        $clientHello = "ClientHello: " . json_encode($config);
        $context->workflowTrace[] = "Send: " . $clientHello;

        $serverHello = "ServerHello: " . json_encode($config);
        $certificateMessage = "CertificateMessage: " . json_encode($config);
        $context->workflowTrace[] = "Receive: [" . $serverHello . ", " . $certificateMessage . "]";

        return $context;
    }
}

$connect = "example.com";
$cipherSuites = ["TLS_RSA_WITH_AES_128_CBC_SHA"];
$certificate = CertificateFetcher::fetchServerCertificate($connect, $cipherSuites);
--EXPECT--
