--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/13)
--FILE--
<?php

$PARAM_WORKFLOW_STATUSES = 'statuses';
$PARAM_WORKFLOW_MODELS = 'models';
$PARAM_WORKFLOW_PAYLOADS = 'payloads';
$PARAM_OLDER_THAN = 'olderThan';
$PARAM_LIMIT = 'limit';
$MS_IN_SECOND = 1000;

$statuses = [];
$models = [];
$payloads = [];
$olderThan = null;

$params = json_decode(file_get_contents('php://input'), true);

$jsonArray = $params[$PARAM_WORKFLOW_STATUSES] ?? [];
foreach ($jsonArray as $status) {
    $statuses[] = $status;
}

$jsonArray = $params[$PARAM_WORKFLOW_MODELS] ?? [];
foreach ($jsonArray as $model) {
    $models[] = $model;
}

$jsonArray = $params[$PARAM_WORKFLOW_PAYLOADS] ?? [];
foreach ($jsonArray as $tmp) {
    $pattern = $tmp['pattern'] ?? '';
    if (!empty(trim($pattern))) {
        $payloads[] = $pattern; // Simulating regex patterns
    }
}

$ts = $params[$PARAM_OLDER_THAN] ?? null;
if ($ts > 0) {
    $olderThan = (new DateTime())->setTimestamp($ts * $MS_IN_SECOND);
}

$limit = $params[$PARAM_LIMIT] ?? $MS_IN_SECOND;

// Simulating the workflow removal process
// WorkflowInstanceRemover::removeWorkflowInstances($models, $statuses, $payloads, $olderThan, $limit);

?>
--EXPECT--
