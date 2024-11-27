<?php

function skipFunction($function): bool {
    if (false
        /* expect input / hang */
     || $function === 'readline'
     || $function === 'readline_read_history'
     || $function === 'readline_write_history'
        /* terminates script */
     || $function === 'exit'
     || $function === 'die'
        /* intentionally violate invariants */
     || $function === 'zend_create_unterminated_string'
     || $function === 'zend_test_array_return'
     || $function === 'zend_test_crash'
     || $function === 'zend_leak_bytes'
        /* mess with output */
     || (is_string($function) && str_starts_with($function, 'ob_'))
     || $function === 'output_add_rewrite_var'
     || $function === 'error_log'
        /* may spend a lot of time waiting for connection timeouts */
     || (is_string($function) && str_contains($function, 'connect'))
     || (is_string($function) && str_starts_with($function, 'snmp'))
     || (is_array($function) && isset($function[0]) && is_object($function[0]) && get_class($function[0]) === mysqli::class
         && in_array($function[1], ['__construct', 'connect', 'real_connect']))
        /* misc */
     || $function === 'mail'
     || $function === 'mb_send_mail'
     || $function === 'pcntl_fork'
     || $function === 'pcntl_rfork'
     || $function === 'posix_kill'
     || $function === 'posix_setrlimit'
     || $function === 'sapi_windows_generate_ctrl_event'
     || $function === 'imagegrabscreen'
    ) {
        return true;
    }
    if (is_array($function) && isset($function[0]) && is_object($function[0]) && $function[0] instanceof SoapServer) {
        /* TODO: Uses fatal errors */
        return true;
    }

    return false;
}

// Main code to collect info from all classes

// Get all declared classes
$classes = get_declared_classes();
if (empty($classes)) {
    echo "No classes declared.\n";
    exit;
}

// Prepare array to hold info for all classes
$allClassesInfo = [];

foreach ($classes as $className) {

    // Skip classes that cannot be instantiated without constructor
    $rc = new ReflectionClass($className);
    if ($rc->isAbstract() || $rc->isInterface() || ($rc->isInternal() && $rc->isFinal())) {
        continue;
    }

    $classInfo = [];
    $classInfo['class_name'] = $className;

    // Collect the class attributes (properties) names
    $properties = $rc->getProperties();
    $propertyNames = [];
    foreach ($properties as $property) {
        $propertyNames[] = $property->getName();
    }
    $classInfo['attributes'] = $propertyNames;

    // Get all methods of the class
    $methods = $rc->getMethods();
    $classInfo['methods'] = [];

    if (!empty($methods)) {
        // Collect method information
        foreach ($methods as $method) {
            $methodName = $method->getName();

            // Skip methods that should be skipped
            if (skipFunction([$className, $methodName])) {
                continue;
            }

            if ($method->isAbstract() || !$method->isPublic()) {
                // Skip abstract or non-public methods
                continue;
            }

            // Collect method info
            $methodInfo = [];
            $methodInfo['name'] = $methodName;

            // Collect parameter count
            $parameters = $method->getParameters();
            $methodInfo['params_count'] = count($parameters);

            $classInfo['methods'][] = $methodInfo;
        }
    }

    $allClassesInfo[] = $classInfo;
}

// Dump all class info into class.json
file_put_contents('class.json', json_encode($allClassesInfo, JSON_PRETTY_PRINT));

