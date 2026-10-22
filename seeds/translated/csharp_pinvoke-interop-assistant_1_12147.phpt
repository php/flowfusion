--TEST--
Translated seed (inferredbugs-csharp: pinvoke-interop-assistant/1)
--FILE--
<?php
$keys = ['SendMessageA','SendMessageW','GetForegroundWindow','CreateWellKnownSid','LPCSTR','LPWSTR','WNDPROC','HOOKPROC','tagPOINT','_SYSTEM_INFO','WM_PAINT','WM_LBUTTONDOWN'];
$storage = [];
foreach ($keys as $k) {
    $storage[$k] = true;
}
$storage['SIZE_T'] = 'int';
$storage['DWORD64'] = 'int';
$storage['HWND'] = 'ptr';
$storage['HMENU'] = 'ptr';
$storage['HACCEL'] = 'ptr';
$storage['HBRUSH'] = 'ptr';
$storage['HFONT'] = 'ptr';
$storage['HDC'] = 'ptr';
$storage['HICON'] = 'ptr';
$xml = "<windows>\n";
foreach ($storage as $k => $v) {
    $xml .= "  <item name=\"" . htmlspecialchars((string)$k, ENT_QUOTES | ENT_XML1) . "\" value=\"" . htmlspecialchars((string)$v, ENT_QUOTES | ENT_XML1) . "\"/>\n";
}
$xml .= "</windows>\n";
file_put_contents("windows.xml", $xml);
file_put_contents("windows.out.h", $xml);
@mkdir("ConsoleTool/bin/Debug", 0777, true);
@mkdir("Data", 0777, true);
file_put_contents("ConsoleTool/bin/Debug/windows.xml", $xml);
file_put_contents("Data/windows.xml", $xml);
$pf = getenv("ProgramFiles");
if ($pf !== false && $pf !== '') {
    $fullInstallTarget = $pf . DIRECTORY_SEPARATOR . "ProductName" . DIRECTORY_SEPARATOR . "Data" . DIRECTORY_SEPARATOR . "windows.xml";
    @mkdir(dirname($fullInstallTarget), 0777, true);
    file_put_contents($fullInstallTarget, $xml);
}
echo $xml;
?>
--EXPECT--
