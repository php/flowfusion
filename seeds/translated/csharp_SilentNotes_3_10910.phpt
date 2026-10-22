--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/3)
--FILE--
<?php
class Ioc {
    private static array $services = [];
    public static function getOrCreate(string $name) {
        return self::$services[$name] ??= new stdClass();
    }
}

class VueBindingShortcut {
    public const KeyEscape = 'Escape';
    public string $key;
    public string $command;
    public function __construct(string $key, string $command) {
        $this->key = $key;
        $this->command = $command;
    }
}

class VueDataBinding {
    public function __construct(public $viewModel, public $view, public array $shortcuts) {}
    public function buildVueScript(): string {
        return implode(';', array_map(fn($s) => $s->key . ':' . $s->command, $this->shortcuts));
    }
    public function startListening(): void {}
}

class ExportViewModel {
    public string $VueDataBindingScript = '';
    public function __construct(...$deps) {}
}

class View {
    public string $html = '';
    public function loadHtml(string $html): void { $this->html = $html; }
}

class ViewService {
    public function generateHtml($viewModel): string {
        return (string)$viewModel->VueDataBindingScript;
    }
}

$viewService = new ViewService();
$view = new View();
$viewModel = new ExportViewModel(
    Ioc::getOrCreate('INavigationService'),
    Ioc::getOrCreate('ILanguageService'),
    Ioc::getOrCreate('ISvgIconService'),
    Ioc::getOrCreate('IThemeService'),
    Ioc::getOrCreate('IBaseUrlService'),
    Ioc::getOrCreate('IFeedbackService'),
    Ioc::getOrCreate('IRepositoryStorageService'),
    Ioc::getOrCreate('IFolderPickerService')
);

$shortcuts = [new VueBindingShortcut(VueBindingShortcut::KeyEscape, 'GoBackCommand')];
$vueBindings = new VueDataBinding($viewModel, $view, $shortcuts);
$viewModel->VueDataBindingScript = $vueBindings->buildVueScript();
$vueBindings->startListening();
$html = $viewService->generateHtml($viewModel);
$view->loadHtml($html);
echo $view->html;
?>
--EXPECT--
