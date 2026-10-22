--TEST--
Translated seed (inferredbugs-java: core/1)
--FILE--
<?php
$servletContext = null;
$SESSION_BEANMAP_KEY = "org.jboss.webbeans.context.session.data";

function beginApplication($context) {
    global $servletContext;
    $servletContext = $context;
}

function endApplication() {
    global $servletContext;
    $servletContext = null;
}

function beginSession($session) {
}

function endSession($session) {
}

function beginRequest($request) {
    global $SESSION_BEANMAP_KEY;
    $manager = "manager"; // Simulating JNDI.lookup("manager")
    $sessionContext = "sessionContext"; // Simulating ManagerImpl.getContext(SessionScoped.class)
    $sessionBeans = $request[$SESSION_BEANMAP_KEY] ?? null;
    $sessionContext = $sessionBeans; // Simulate setting beans
}

function endRequest($request) {
}

$context = "appContext"; // Simulated context
beginApplication($context);
$request = [$SESSION_BEANMAP_KEY => "sessionData"]; // Simulated request
beginRequest($request);
endRequest($request);
endApplication();
?>
--EXPECT--
