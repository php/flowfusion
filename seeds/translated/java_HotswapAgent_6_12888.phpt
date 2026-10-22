--TEST--
Translated seed (inferredbugs-java: HotswapAgent/6)
--FILE--
<?php

class LogConfigurationHelper {
    const LOGGER_PREFIX = 'LOGGER';
    const LOGFILE = 'LOGFILE';

    public static function configureLog(array $properties) {
        foreach ($properties as $property => $value) {
            if (strpos($property, self::LOGGER_PREFIX) === 0) {
                $classPrefix = self::getClassPrefix($property);
                $level = self::getLevel($property, $value);

                if ($level !== null) {
                    if ($classPrefix === null) {
                        // Simulate setting global log level
                    } else {
                        // Simulate setting log level for a specific class/package
                    }
                }
            } elseif ($property === self::LOGFILE) {
                $logfile = $value;
                if (!file_exists($logfile)) {
                    // Simulate logging an error
                }
                // Simulate setting print stream to logfile
            }
        }
    }

    private static function getLevel($property, $levelName) {
        $validLevels = ['DEBUG', 'INFO', 'WARNING', 'ERROR', 'FATAL'];
        if (in_array(strtoupper($levelName), $validLevels)) {
            return strtoupper($levelName);
        } else {
            // Simulate logging a warning
            return null;
        }
    }

    private static function getClassPrefix($property) {
        if ($property === self::LOGGER_PREFIX) {
            return null;
        } else {
            return substr($property, strlen(self::LOGGER_PREFIX) + 1);
        }
    }
}

$properties = [
    'LOGGER.my.package' => 'INFO',
    'LOGGER' => 'DEBUG',
    'LOGFILE' => 'path/to/logfile.log'
];

LogConfigurationHelper::configureLog($properties);
--EXPECT--
