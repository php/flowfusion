--TEST--
Translated seed (inferredbugs-csharp: USharp/4)
--FILE--
<?php
$module = (object)['Name' => 'Core'];
$field = (object)[
    'kind' => 'class',
    'name' => 'K2_Test',
    'flags' => 0x80000001,
    'classFlags' => 0x00000003,
    'config' => 'inherit',
    'path' => '/Script/Core.Test',
    'meta' => ['Blueprintable' => true],
];
$attrs = [];
if (($field->flags & 0x80000000) !== 0) {
    $attrs[] = 'UFunction(Flags=0x' . strtoupper(str_pad(dechex($field->flags & 0xFFFFFFFF), 8, '0', STR_PAD_LEFT)) . ', K2=true)';
}
if (($field->kind === 'class')) {
    if (($field->classFlags & 0x00000001) !== 0) {
        $attrs[] = 'Abstract';
    }
    if (strcasecmp((string)$field->config, 'inherit') !== 0 && $field->config !== '') {
        $attrs[] = 'UClass(Flags=(ClassFlags)0x' . strtoupper(str_pad(dechex($field->classFlags & 0xFFFFFFFF), 8, '0', STR_PAD_LEFT)) . ', Config="' . $field->config . '")';
    } else {
        $attrs[] = 'UClass(Flags=(ClassFlags)0x' . strtoupper(str_pad(dechex($field->classFlags & 0xFFFFFFFF), 8, '0', STR_PAD_LEFT)) . ')';
    }
    if (!empty($field->meta['Blueprintable'])) {
        $attrs[] = 'Blueprintable';
    }
}
$attrs[] = 'UMetaPath("' . $field->path . '", "' . $module->Name . '", UnrealModuleType.Game)';
echo '[' . implode(', ', $attrs) . ']' . PHP_EOL;
--EXPECT--
