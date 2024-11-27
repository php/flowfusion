<?php
// Define str_starts_with and str_contains for PHP versions < 8.0
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return strpos($haystack, $needle) === 0;
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}

function collect_functions($vars) {
    // Get all loaded extensions
    $extensions = get_loaded_extensions();

    // Initialize an array to hold all internal and extension functions
    $allInternalFunctions = array();

    // Get all defined functions
    $definedFunctions = get_defined_functions();
    $internalFunctions = $definedFunctions['internal'];
    $allInternalFunctions = array_merge($allInternalFunctions, $internalFunctions);

    // Iterate over each extension to get its functions
    foreach ($extensions as $extension) {
        $functions = get_extension_funcs($extension);
        if ($functions !== false) {
            $allInternalFunctions = array_merge($allInternalFunctions, $functions);
        }
    }

    // Remove duplicates
    $allInternalFunctions = array_unique($allInternalFunctions);

    // Define the skipFunction
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
            return false;
        }

        // These conditions won't be true in this context but are included for completeness
        if (is_array($function) && get_class($function[0]) === mysqli::class
            && in_array($function[1], ['__construct', 'connect', 'real_connect'])) {
            return false;
        }

        if (is_array($function) && $function[0] instanceof SoapServer) {
            /* TODO: Uses fatal errors */
            return false;
        }

        return true;
    }

    // Filter functions using skipFunction
    $allInternalFunctions = array_filter($allInternalFunctions, 'skipFunction');

    // Sort the functions
    sort($allInternalFunctions);

    $functionInfoList = [];

    foreach ($allInternalFunctions as $functionName) {
        try {
            // Get reflection of the function to determine the parameters
            $reflection = new ReflectionFunction($functionName);
            $numParams = $reflection->getNumberOfParameters();
            $params = $reflection->getParameters();

            // Prepare parameter info
            $paramInfos = [];
            foreach ($params as $param) {
                $paramDetails = [
                    'name' => $param->getName(),
                    'type' => $param->hasType() ? (string)$param->getType() : null,
                    'is_optional' => $param->isOptional(),
                    'default_value' => null,
                ];

                // Suppress deprecation warnings when getting default value
                if ($param->isDefaultValueAvailable()) {
                    $originalErrorReporting = error_reporting();
                    error_reporting($originalErrorReporting & ~E_DEPRECATED);
                    $defaultValue = $param->getDefaultValue();
                    error_reporting($originalErrorReporting);

                    // Convert default value to a JSON-serializable format
                    if (is_scalar($defaultValue) || is_null($defaultValue)) {
                        $paramDetails['default_value'] = $defaultValue;
                    } else {
                        // Convert non-scalar values to their string representation
                        $paramDetails['default_value'] = var_export($defaultValue, true);
                    }
                }

                $paramInfos[] = $paramDetails;
            }

            // Collect function info
            $functionInfo = [
                'name' => $functionName,
                'num_params' => $numParams,
                'params' => $paramInfos,
            ];

            $functionInfoList[] = $functionInfo;

        } catch (\Throwable $e) {
            // Handle any exceptions or errors
            // You can log the error if needed
        }
    }

    // Write the function info list to JSON file
    $json = json_encode($functionInfoList, JSON_PRETTY_PRINT);

    // Check if json_encode failed
    if ($json === false) {
        echo "json_encode error: " . json_last_error_msg() . "\n";
        // Optionally, you can handle the error further here
    } else {
        file_put_contents('./apis.json', $json);
    }
}

// Call the function
collect_functions([]);
