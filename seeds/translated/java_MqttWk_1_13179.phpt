--TEST--
Translated seed (inferredbugs-java: MqttWk/1)
--FILE--
<?php

$sum = 0;
$topic = "test_topic"; // Example topic
$messages = ["value1", "value2", "value3"]; // Simulated incoming messages

while (true) {
    foreach ($messages as $key => $value) {
        echo sprintf("offset = %d, key = %d, value = %s\n", $key, $key, $value);
        echo sprintf("总计收到 %d条\n", ++$sum);
    }
    usleep(500000); // Sleep for 500ms to simulate Kafka poll delay
}
--EXPECT--
