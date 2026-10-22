--TEST--
Translated seed (inferredbugs-csharp: azure-functions-durable-extension/2)
--FILE--
<?php
class DurabilityProvider {
    public int $maximumDelayTime = 0;
    public int $longRunningTimerIntervalLength = 0;
}

class AzureStorageDurabilityProvider extends DurabilityProvider {}

class AzureStorageDurabilityProviderFactory {
    public function getDurabilityProvider($attribute = null): DurabilityProvider {
        return new AzureStorageDurabilityProvider();
    }
}

$factory = new AzureStorageDurabilityProviderFactory();
$provider = $factory->getDurabilityProvider();
$provider->maximumDelayTime = 60;
$provider->longRunningTimerIntervalLength = 25;
$provider->maximumDelayTime += $provider->longRunningTimerIntervalLength;
$provider->longRunningTimerIntervalLength = intdiv($provider->maximumDelayTime, 2);
echo $provider->maximumDelayTime . ':' . $provider->longRunningTimerIntervalLength . PHP_EOL;
--EXPECT--
