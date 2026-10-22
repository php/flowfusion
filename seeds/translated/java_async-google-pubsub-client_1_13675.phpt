--TEST--
Translated seed (inferredbugs-java: async-google-pubsub-client/1)
--FILE--
<?php

$PULLER_CONCURRENCY = 128;

$project = "default_project"; // Simulate project retrieval

$subscription = getenv("GOOGLE_PUBSUB_SUBSCRIPTION");
if ($subscription === false) {
    fwrite(STDERR, "Please specify a subscription using the GOOGLE_PUBSUB_SUBSCRIPTION environment variable.\n");
    exit(1);
}

echo "Consuming from GOOGLE_PUBSUB_SUBSCRIPTION='$subscription'\n";

$receives = 0;

while (true) {
    // Simulate handling message by simply incrementing the receive counter
    $receives++;
    usleep(1000 * 1000); // Sleep 1 second
}
--EXPECT--
