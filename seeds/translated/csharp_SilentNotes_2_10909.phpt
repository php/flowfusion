--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/2)
--FILE--
<?php
$viewModel = null;
$bindings = null;

$variables = [];
$redirectedFrom = null;

$viewModel = new stdClass();
$viewModel->navigationService = new stdClass();
$viewModel->languageService = new stdClass();
$viewModel->svgIconService = new stdClass();
$viewModel->themeService = new stdClass();
$viewModel->baseUrlService = new stdClass();
$viewModel->settingsService = new stdClass();
$viewModel->storyBoardService = new stdClass();

$bindings = new stdClass();
$bindings->model = $viewModel;
$bindings->view = new stdClass();

$viewModel->vueDataBindingScript = json_encode([
    'nav' => spl_object_id($viewModel->navigationService),
    'lang' => spl_object_id($viewModel->languageService),
    'svg' => spl_object_id($viewModel->svgIconService),
    'theme' => spl_object_id($viewModel->themeService),
    'base' => spl_object_id($viewModel->baseUrlService),
    'settings' => spl_object_id($viewModel->settingsService),
    'story' => spl_object_id($viewModel->storyBoardService),
]);

$bindings->started = true;

$html = '<html><body>' . htmlspecialchars($viewModel->vueDataBindingScript, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</body></html>';
echo $html;
?>
--EXPECT--
