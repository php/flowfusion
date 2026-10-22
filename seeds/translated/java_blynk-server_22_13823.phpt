--TEST--
Translated seed (inferredbugs-java: blynk-server/22)
--FILE--
<?php

class OTALogic {
    private $otaManager;
    private const OTA_DIR = '/static/ota/';
    private $tokenManager;
    private $sessionDao;

    public function __construct($otaManager, $tokenManager, $sessionDao) {
        $this->otaManager = $otaManager;
        $this->tokenManager = $tokenManager;
        $this->sessionDao = $sessionDao;
    }

    public function startOTA($filename, $token) {
        $tokenValue = $this->tokenManager->getTokenValueByToken($token);
        if ($tokenValue === null) {
            echo "Invalid token.\n";
            return;
        }

        $user = $tokenValue->user;
        $dashId = $tokenValue->dashId;
        $deviceId = $tokenValue->deviceId;

        if ($user === null) {
            echo "Invalid auth credentials.\n";
            return;
        }

        $session = $this->sessionDao->userSession[new UserKey($user)];
        if ($session === null) {
            echo "Device wasn't connected yet.\n";
            return;
        }

        $otaFile = self::OTA_DIR . ($filename === null ? "firmware_ota.bin" : $filename);
        $body = $this->otaManager->buildOTAInitCommandBody($otaFile);
        if ($session->sendMessageToHardware('BLYNK_INTERNAL', 7777, $body)) {
            echo "No device in session.\n";
            return;
        }

        $dash = $user->profile->getDashById($dashId);
        $device = $dash->getDeviceById($deviceId);
        $device->updateOTAInfo($user->email);
    }

    public function stopOTA($initiator) {
        $this->otaManager->stop($initiator);
    }
}

$otaLogic = new OTALogic($otaManager, $tokenManager, $sessionDao);
$otaLogic->startOTA(null, 'dummy_token');
--EXPECT--
