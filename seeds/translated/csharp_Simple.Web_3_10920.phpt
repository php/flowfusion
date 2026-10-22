--TEST--
Translated seed (inferredbugs-csharp: Simple.Web/3)
--FILE--
<?php
class SimpleTemplateBase {
    public $Var;
    public $Writer;
    public $handler;
    public $model;
    function SetHandler($h) { $this->handler = $h; }
    function SetModel($m) { $this->model = $m; }
    function Execute() {
        if (is_resource($this->Writer)) {
            fwrite($this->Writer, (string)($this->Var['x'] ?? ''));
        } else {
            echo (string)($this->Var['x'] ?? '');
        }
    }
}

class ViewNotFoundException extends Exception {}

class RazorViews {
    function GetViewType($viewPath) {
        return ($viewPath === 'ok') ? 'DemoTemplate' : null;
    }
    function GetViewTypeForHandlerAndModelType($handlerType, $modelType) {
        return ($handlerType === 'stdClass' || $modelType === 'stdClass') ? 'DemoTemplate' : null;
    }
}

class DemoTemplate extends SimpleTemplateBase {}

$content = new stdClass();
$content->ViewPath = null;
$content->Handler = new stdClass();
$content->Model = null;
$content->Variables = ['x' => 'ok'];

$razorViews = new RazorViews();
if ($content->ViewPath !== null) {
    $viewType = $razorViews->GetViewType($content->ViewPath);
} else {
    $handlerType = $content->Handler !== null ? get_class($content->Handler) : null;
    $modelType = $content->Model !== null ? get_class($content->Model) : null;
    $viewType = $razorViews->GetViewTypeForHandlerAndModelType($handlerType, $modelType);
}

if ($viewType === null) {
    throw new ViewNotFoundException();
}

$instance = new $viewType();
$instance->SetHandler($content->Handler);
$instance->SetModel($content->Model);

$viewData = [];
foreach ($content->Variables as $k => $v) {
    $viewData[$k] = $v;
}

$instance->Var = $viewData;
$instance->Writer = fopen('php://output', 'w');
$instance->Execute();
fclose($instance->Writer);
?>
--EXPECT--
