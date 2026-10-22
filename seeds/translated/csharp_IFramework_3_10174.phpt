--TEST--
Translated seed (inferredbugs-csharp: IFramework/3)
--FILE--
<?php
class AddArgs {
    public int $value;
    function __construct(int $addValue) { $this->value = $addValue; }
}

class MyModel {
    public int $value = 2;
}

class MyViewModel {
    public MyModel $model;
    public int $value = 0;
    function __construct(MyModel $model) {
        $this->model = $model;
        $this->value = 5;
        $this->model->value = $this->value;
    }
    function syncModelValue() { $this->value = $this->model->value; }
    function listen($message) { if ($message instanceof AddArgs) $this->value += $message->value; }
}

class MyView {
    public MyViewModel $context;
    function __construct(MyViewModel $context) { $this->context = $context; }
    function publish($message) {
        if ($message === null) $this->context->listen($message);
        else $this->context->listen($message);
        $v = $this->context->value;
        echo "View:数据出现更改，当前的值为：$v\n";
    }
    function publishAddValue(int $i) { $this->publish(new AddArgs($i)); }
}

$model = new MyModel();
$vm = new MyViewModel($model);
$view = new MyView($vm);
$view->publish(null);
$view->publishAddValue(233);
$vm->syncModelValue();
echo $vm->value . "\n";
echo $model->value . "\n";
?>
--EXPECT--
