--TEST--
Translated seed (inferredbugs-csharp: IFramework/2)
--FILE--
<?php
class MyModel { public int $value = 2; }

class MyViewModel {
    public MyModel $model;
    public int $value = 0;
    public function __construct(MyModel $model) { $this->model = $model; }
    public function initialize(): void { $this->value = 5; $this->model->value = $this->value; }
    public function syncModelValue(): void { $this->value = $this->model->value; $this->value++; $this->model->value = $this->value; }
    public function listen($message): void { echo "Message rec\n"; }
}

class MyView {
    public MyViewModel $context;
    public function __construct(MyViewModel $context) { $this->context = $context; }
    public function bindProperty(): void {
        $value = $this->context->value;
        echo $value . "\n";
        echo "Message pub\n";
        $this->context->listen(null);
    }
}

$model = new MyModel();
$vm = new MyViewModel($model);
$view = new MyView($vm);
$vm->initialize();
$view->bindProperty();
$vm->syncModelValue();
?>
--EXPECT--
