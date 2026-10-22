--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/10)
--FILE--
<?php
$SAVE_THRESHOLD = 1000;
$DEFAULT_ESTIMATED_TOTAL = 10000;
$DEFAULT_BATCH_SIZE = 100;
$STATE_COMPLETE = 'complete';
$STATE_NOT_STARTED = 'not_started';
$STATE_RUNNING = 'running';

$properties = [
    'KEY_CURRENT_BATCH' => '',
    'KEY_INITIALIZED' => false,
    'KEY_JOB_NAME' => 'job1',
    'KEY_BATCH_SIZE' => $DEFAULT_BATCH_SIZE,
    'KEY_ESTIMATED_TOTAL' => $DEFAULT_ESTIMATED_TOTAL,
    'KEY_STATE' => $STATE_NOT_STARTED,
];

$nodes = range(1, 5000);
$total = 0;
$currentBatch = null;

foreach ($nodes as $node) {
    if ($currentBatch === null) {
        $currentBatch = "batch_{$total}";
    }

    $batchItemPath = "{$currentBatch}/item_{$total}";
    
    // Simulate property setting akin to JCR's property setting
    $properties["KEY_PATH_{$total}"] = $node;

    $total++;

    if ($total % $SAVE_THRESHOLD === 0) {
        // Simulate save operation 
    }
}

// Completing the last batch
$properties['KEY_NEXT_BATCH'] = $STATE_COMPLETE;

if ($total % $SAVE_THRESHOLD !== 0) {
    // Simulate final save operation
}

$properties['KEY_TOTAL'] = $total;
$properties['KEY_INITIALIZED'] = true;
?>
--EXPECT--
