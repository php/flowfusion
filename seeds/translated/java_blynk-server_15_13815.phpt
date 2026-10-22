--TEST--
Translated seed (inferredbugs-java: blynk-server/15)
--FILE--
<?php

class UpdateDeviceLogic {

    public function messageReceived($ctx, $user, $message) {
        $split = explode(";", $message['body']);

        if (count($split) < 2) {
            throw new Exception("Wrong income message format.");
        }

        $dashId = (int)$split[0];
        $deviceString = $split[1];

        if ($deviceString === null || $deviceString === "") {
            throw new Exception("Income device message is empty.");
        }

        $dash = $user['profile']->getDashByIdOrThrow($dashId);

        $newDevice = json_decode($deviceString);

        $existingDevice = $dash->getDeviceById($newDevice->id);

        $existingDevice->update($newDevice);
        $dash->updatedAt = time() * 1000;
        $user->lastModifiedTs = $dash->updatedAt;

        // Mimicking the write and flush operation
        echo json_encode(['status' => 'ok', 'id' => $message['id']]);
    }

}

$logic = new UpdateDeviceLogic();
$message = ['body' => '1;{"id":123,"name":"Device1"}', 'id' => 1];
$user = ['profile' => new class {
    public function getDashByIdOrThrow($id) {
        return new class {
            public $updatedAt;
            public function getDeviceById($id) {
                return new class {
                    public function update($device) {}
                };
            }
        };
    }
}];

$logic->messageReceived(null, $user, $message);
--EXPECT--
