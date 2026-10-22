--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/62)
--FILE--
<?php

class RemoteAppiumManager {

    public function startAppiumServer($host) {
        echo "**************************************************************************\n";
        echo "Starting Appium Server on host " . $host . "\n";
        echo "**************************************************************************\n";
        
        $serverPath = null; // Simulating CapabilityManager.getInstance()->getAppiumServerPath($host);
        $serverPort = null; // Simulating CapabilityManager.getInstance()->getAppiumServerPort($host);

        if ($serverPath === null && $serverPort === null) {
            echo "Picking Default Path for AppiumServiceBuilder\n";
            $this->getResponse("http://$host:4567/appium/start");
        } elseif ($serverPath !== null && $serverPort !== null) {
            echo "Picking UserSpecified Path & Port for AppiumServiceBuilder\n";
            $this->getResponse("http://$host:4567/appium/start?URL=$serverPath&PORT=$serverPort");
        } elseif ($serverPath !== null) {
            echo "Picking UserSpecified Path & Using default Port for AppiumServiceBuilder\n";
            $this->getResponse("http://$host:4567/appium/start?URL=$serverPath");
        } elseif ($serverPort !== null) {
            echo "Picking Default Path & User Port for AppiumServiceBuilder\n";
            $this->getResponse("http://$host:4567/appium/start?PORT=$serverPort");
        }

        $status = json_decode($this->getResponse("http://$host:4567/appium/isRunning"), true)['status'];
        if ($status) {
            echo "***************************************************************\n";
            echo "Appium Server started successfully on  " . $host . "\n";
            echo "****************************************************************\n";
        }
    }

    private function getResponse($url) {
        return file_get_contents($url);
    }

}

$manager = new RemoteAppiumManager();
$manager->startAppiumServer("localhost");
--EXPECT--
