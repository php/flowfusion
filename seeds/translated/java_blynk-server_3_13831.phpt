--TEST--
Translated seed (inferredbugs-java: blynk-server/3)
--FILE--
<?php

class SaveProfileHandler {

    private $fileManager;
    private $userRegistry;

    public function __construct($fileManager, $userRegistry) {
        $this->fileManager = $fileManager;
        $this->userRegistry = $userRegistry;
    }

    public function handle($userProfileString, $messageId) {
        if ($userProfileString === null || $userProfileString === '') {
            echo "Error: Income profile message is empty.\n";
            echo json_encode(['id' => $messageId, 'status' => 'INVALID_COMMAND_FORMAT']) . "\n";
            return;
        }

        $userProfile = json_decode($userProfileString, true);
        if ($userProfile === null) {
            echo "Error: Wrong user profile message format.\n";
            echo json_encode(['id' => $messageId, 'status' => 'INVALID_COMMAND_FORMAT']) . "\n";
            return;
        }

        $authUser = $this->findUserByChannel();

        $authUser['profile'] = $userProfile;
        $profileSaved = $this->fileManager->overrideUserFile($authUser);

        if ($profileSaved) {
            echo json_encode(['id' => $messageId, 'status' => 'OK']) . "\n";
        } else {
            echo json_encode(['id' => $messageId, 'status' => 'SERVER_ERROR']) . "\n";
        }
    }

    private function findUserByChannel() {
        // Simulate user retrieval logic
        return ['id' => 1, 'profile' => []];
    }
}

$fileManager = new class {
    public function overrideUserFile($user) {
        return true; // Simulate successful save
    }
};

$userRegistry = null; // Assuming no use in current context
$handler = new SaveProfileHandler($fileManager, $userRegistry);
$handler->handle('{"name":"John Doe"}', 123);
--EXPECT--
