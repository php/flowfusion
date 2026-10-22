--TEST--
Translated seed (inferredbugs-csharp: ecommon/2)
--FILE--
<?php
function io_action_recursive($name, $getContextInfo, $action, $retryTimes, $maxRetryTimes, $continueRetryWhenRetryFailed = false, $retryInterval = 1000) {
    try {
        $action($name, $getContextInfo, $retryTimes);
    } catch (Throwable $ex) {
        if ($retryTimes >= $maxRetryTimes) {
            if (!$continueRetryWhenRetryFailed) {
                throw $ex;
            }
            usleep($retryInterval * 1000);
        }
        io_action_recursive($name, $getContextInfo, $action, $retryTimes + 1, $maxRetryTimes, $continueRetryWhenRetryFailed, $retryInterval);
    }
}

function io_func_recursive($name, $getContextInfo, $func, $retryTimes, $maxRetryTimes, $continueRetryWhenRetryFailed = false, $retryInterval = 1000) {
    try {
        return $func($name, $getContextInfo, $retryTimes);
    } catch (Throwable $ex) {
        if ($retryTimes >= $maxRetryTimes) {
            if (!$continueRetryWhenRetryFailed) {
                throw $ex;
            }
            usleep($retryInterval * 1000);
        }
        return io_func_recursive($name, $getContextInfo, $func, $retryTimes + 1, $maxRetryTimes, $continueRetryWhenRetryFailed, $retryInterval);
    }
}

$getContextInfo = function () {
    return "ctx";
};

$attempts = 0;
io_action_recursive("action", $getContextInfo, function ($name, $getContextInfo, $retryTimes) use (&$attempts) {
    $attempts++;
    if ($retryTimes < 2) {
        throw new RuntimeException("transient");
    }
}, 0, 3, false, 1);

$result = io_func_recursive("func", $getContextInfo, function ($name, $getContextInfo, $retryTimes) use (&$attempts) {
    if ($retryTimes < 1) {
        throw new RuntimeException("transient");
    }
    return $attempts;
}, 0, 2, false, 1);

echo $result, PHP_EOL;
--EXPECT--
