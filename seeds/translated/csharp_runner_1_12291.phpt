--TEST--
Translated seed (inferredbugs-csharp: runner/1)
--FILE--
<?php
$registered = ['stop-commands' => true, 'notice' => true, 'set-env' => true, 'add-path' => true, 'echo' => true];
$stop = false;
$token = null;
$env = ['ACTIONS_ALLOW_UNSUPPORTED_STOP_COMMAND_TOKENS' => 'false', 'ACTIONS_ALLOW_UNSUPPORTED_COMMANDS' => 'false'];
$echo = false;
$inputs = [
    '::stop-commands::notice',
    '::notice::hello',
    '::notice::notice',
    '::echo::ON',
    '::echo::OFF',
];
foreach ($inputs as $input) {
    if (!preg_match('/^::([^\s:]+)(?:\s+([^:]*))?::(.*)$/', $input, $m)) continue;
    $cmd = strtolower($m[1]);
    $data = $m[3];
    if ($stop) {
        if ($token !== null && strcasecmp($cmd, $token) === 0) {
            $stop = false;
            $token = null;
        }
        continue;
    }
    if ($cmd === 'stop-commands') {
        $token = $data;
        $stop = true;
        if ($token !== '' && strlen($token) > 6) $registered[$token] = true;
        continue;
    }
    if (!isset($registered[$cmd])) continue;
    if ($cmd === 'echo') {
        $v = strtoupper(trim($data));
        if ($v === 'ON') $echo = true;
        elseif ($v === 'OFF') $echo = false;
    }
}
?>
--EXPECT--
