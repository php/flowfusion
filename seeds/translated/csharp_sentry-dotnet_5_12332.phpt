--TEST--
Translated seed (inferredbugs-csharp: sentry-dotnet/5)
--FILE--
<?php
class User {
    public ?string $Email = null;
    public ?string $Id = null;
    public ?string $Username = null;
    public ?array $InternalOther = null;
    public ?string $IpAddress = null;
}
class EventLike {
    public User $User;
    public array $Fingerprint = [];
    public array $Tags = [];
    public array $Extras = [];
    public array $Breadcrumbs = [];
    function __construct() { $this->User = new User(); }
    function SetExtra(string $k, $v): void { $this->Extras[$k] = $v; }
    function SetTag(string $k, string $v): void { $this->Tags[$k] = $v; }
    function AddBreadcrumb($b): void { $this->Breadcrumbs[] = $b; }
}
class Scope extends EventLike {
    public array $EventProcessors = [];
    public array $ExceptionProcessors = [];
    public object $Options;
    public array $Attachments = [];
    function __construct() { parent::__construct(); $this->Options = new class { function GetAllEventProcessors() { return []; } function GetAllExceptionProcessors() { return []; } }; }
}
$e = new EventLike();
$e->User->InternalOther = [];
$hasUser = $e->User->Email !== null || $e->User->Id !== null || $e->User->Username !== null || count($e->User->InternalOther) > 0 || $e->User->IpAddress !== null;
$e->Fingerprint = array_values((function () { yield "a"; yield "b"; })());
foreach (["k1" => null, "k2" => 1] as $k => $v) $e->SetExtra($k, $v);
foreach (["t1" => "v1", "t2" => "v2"] as $k => $v) $e->SetTag($k, $v);
unset($e->Tags["t1"]);
$s = new Scope();
$s->EventProcessors[] = fn($ev) => $ev;
$s->ExceptionProcessors[] = new stdClass();
foreach ($s->Options->GetAllEventProcessors() as $p) {}
foreach ($s->EventProcessors as $p) {}
foreach ($s->Options->GetAllExceptionProcessors() as $p) {}
foreach ($s->ExceptionProcessors as $p) {}
$s->Attachments[] = ["type" => "default", "data" => fopen("php://memory", "r"), "fileName" => "x.txt", "length" => 0, "contentType" => null];
echo "";
?>
--EXPECT--
