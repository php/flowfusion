--TEST--
Translated seed (inferredbugs-csharp: ZKEACMS/8)
--FILE--
<?php
class Message {
    public $Content = '';
    public $IsHtml = false;
}

class RazorEmailMessage extends Message {
    public $TemplatePath;
    public $Model;
}

class EmailNotificationProvider {
    public function Send(Message $notice) {
        $notice->Content = (string)$notice->Content;
    }
}

class RazorEmailNotificationProvider extends EmailNotificationProvider {
    private $viewRenderService;
    public function __construct($viewRenderService) {
        $this->viewRenderService = $viewRenderService;
    }
    public function Send(Message $notice) {
        $razorEmailNotice = $notice;
        $razorEmailNotice->IsHtml = true;
        $razorEmailNotice->Content = $this->viewRenderService->Render($razorEmailNotice->TemplatePath, $razorEmailNotice->Model);
        parent::Send($razorEmailNotice);
    }
}

class ViewRenderService {
    public function Render($templatePath, $model) {
        return is_array($model) ? ($templatePath . ':' . implode(',', array_keys($model))) : ($templatePath . ':' . (string)$model);
    }
}

$provider = new RazorEmailNotificationProvider(new ViewRenderService());
$msg = new RazorEmailMessage();
$msg->TemplatePath = 'mail/template';
$msg->Model = ['name' => 'Alice', 'id' => 1];
$provider->Send($msg);
echo $msg->Content;
?>
--EXPECT--
