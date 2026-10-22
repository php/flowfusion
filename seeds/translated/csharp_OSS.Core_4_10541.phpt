--TEST--
Translated seed (inferredbugs-csharp: OSS.Core/4)
--FILE--
<?php
$base = __DIR__ . DIRECTORY_SEPARATOR . 'Templates';
@mkdir($base, 0777, true);
$template = $base . DIRECTORY_SEPARATOR . 'sample.txt';
file_put_contents($template, "{module_name}|{solution_name}|{domain_project_name}|{domain_opened_project_name}|{service_project_name}|{service_opened_project_name}|{repository_project_name}|{webapi_project_name}");

$ss = (object)[
    'module_name' => 'mod',
    'solution_name' => 'sol',
    'domain_project' => (object)['name' => 'dom'],
    'domain_opened_project' => (object)['name' => 'dom_open'],
    'service_project' => (object)['name' => 'svc'],
    'service_opened_project' => (object)['name' => 'svc_open'],
    'rep_project' => (object)['name' => 'rep'],
    'webapi_project' => (object)['name' => 'web'],
];

$content = file_get_contents($template);
$content = str_replace(
    ['{module_name}', '{solution_name}', '{domain_project_name}', '{domain_opened_project_name}', '{service_project_name}', '{service_opened_project_name}', '{repository_project_name}', '{webapi_project_name}'],
    [$ss->module_name, $ss->solution_name, $ss->domain_project->name, $ss->domain_opened_project->name, $ss->service_project->name, $ss->service_opened_project->name, $ss->rep_project->name, $ss->webapi_project->name],
    $content
);

$out = __DIR__ . DIRECTORY_SEPARATOR . 'out.txt';
if (file_exists($out)) unlink($out);
file_put_contents($out, $content . PHP_EOL);
--EXPECT--
