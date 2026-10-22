--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/80)
--FILE--
<?php
class Agent {
    public static function setup($components) {}
    public static function subscribe(...$subscribers) {}
}

class AgentComponents {}
class HttpDiagnosticsSubscriber {}
class EfCoreDiagnosticsSubscriber {}
class SqlClientDiagnosticSubscriber {}
class AspNetCoreErrorDiagnosticsSubscriber {}
class AspNetCorePageLoadDiagnosticSubscriber {}

Agent::setup(new AgentComponents());
Agent::subscribe(
    new HttpDiagnosticsSubscriber(),
    new EfCoreDiagnosticsSubscriber(),
    new SqlClientDiagnosticSubscriber(),
    new AspNetCoreErrorDiagnosticsSubscriber(),
    new AspNetCorePageLoadDiagnosticSubscriber()
);
?>
--EXPECT--
