--TEST--
Translated seed (inferredbugs-java: closure-compiler/8)
--FILE--
<?php

define('UNKNOWN_TWEAK_WARNING', 'no tweak registered with ID %s');
define('TWEAK_MULTIPLY_REGISTERED_ERROR', 'Tweak %s has already been registered.');
define('NON_LITERAL_TWEAK_ID_ERROR', 'tweak ID must be a string literal');
define('NON_GLOBAL_TWEAK_INIT_ERROR', 'tweak declaration %s must occur in the global scope');
define('TWEAK_OVERRIDE_AFTER_REGISTERED_ERROR', 'Cannot override the default value of tweak %s after it has been registered');
define('INVALID_TWEAK_ID_ERROR', 'tweak ID contains illegal characters. Only letters, numbers, _ and . are allowed');

class TweakFunction {
    const REGISTER_BOOLEAN = 'goog.tweak.registerBoolean';
    const REGISTER_NUMBER = 'goog.tweak.registerNumber';
    const REGISTER_STRING = 'goog.tweak.registerString';
    const OVERRIDE_DEFAULT_VALUE = 'goog.tweak.overrideDefaultValue';
    const GET_BOOLEAN = 'goog.tweak.getBoolean';
    const GET_NUMBER = 'goog.tweak.getNumber';
    const GET_STRING = 'goog.tweak.getString';
}

$compilerDefaultValueOverrides = [];
$tweakInfos = [];

function addTweak($id, $type, $value) {
    global $tweakInfos;
    if (isset($tweakInfos[$id])) {
        echo sprintf(TWEAK_MULTIPLY_REGISTERED_ERROR, $id);
        return;
    }
    $tweakInfos[$id] = ['type' => $type, 'value' => $value, 'registered' => true];
}

function overrideDefaultValue($id, $value) {
    global $tweakInfos;
    if (!isset($tweakInfos[$id])) {
        echo sprintf(UNKNOWN_TWEAK_WARNING, $id);
        return;
    }
    if ($tweakInfos[$id]['registered']) {
        echo sprintf(TWEAK_OVERRIDE_AFTER_REGISTERED_ERROR, $id);
        return;
    }
    $tweakInfos[$id]['value'] = $value;
}

function getTweak($id) {
    global $tweakInfos;
    if (!isset($tweakInfos[$id])) {
        echo sprintf(UNKNOWN_TWEAK_WARNING, $id);
        return null;
    }
    return $tweakInfos[$id]['value'];
}

addTweak('tweak1', 'boolean', false);
overrideDefaultValue('tweak1', true);
$value = getTweak('tweak1');
--EXPECT--
