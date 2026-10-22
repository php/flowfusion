--TEST--
Translated seed (inferredbugs-java: apollo/2)
--FILE--
<?php

$apolloTestClient = signupAndLogin();
$apolloTestAdminClient = getAndLoginApolloTestAdminClient();

$environment = createAndSubmitEnvironment($apolloTestClient);
$service = createAndSubmitService($apolloTestClient);
$deployableVersion = createAndSubmitDeployableVersion($apolloTestClient, $service);

$blocker = createAndSubmitBlocker($apolloTestAdminClient, "unconditional", "{}", null, null);

try {
    createAndSubmitDeployment($apolloTestClient, $environment, $service, $deployableVersion);
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Deployment is currently blocked") === false) {
        throw $e;
    }
}

$blocker->setActive(false);
$apolloTestAdminClient->updateBlocker($blocker);

createAndSubmitDeployment($apolloTestClient, $environment, $service, $deployableVersion);
--EXPECT--
