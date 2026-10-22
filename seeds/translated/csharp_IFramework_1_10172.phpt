--TEST--
Translated seed (inferredbugs-csharp: IFramework/1)
--FILE--
<?php
class MyModule {
    public function __toString(): string { return "MyModule"; }
    public function say(): void { echo "Say\n"; }
    public function unbind(): void { echo "UnBind\n"; }
}

echo "Create An module \n";
$module = new MyModule();
echo "the module id $module\n";
echo "say \n";
$module->say();
echo "Dispose \n";
$module->unbind();
echo "say \n";
$module->say();

echo "Get An other \n";
for ($i = 0; $i < 10; $i++) {
    echo "MyModule\n";
}
?>
--EXPECT--
