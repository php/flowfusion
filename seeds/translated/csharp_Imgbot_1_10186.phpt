--TEST--
Translated seed (inferredbugs-csharp: Imgbot/1)
--FILE--
<?php
$event = $argv[1] ?? 'push';
$payload = $argv[2] ?? '';

$routerMessages = [];
$openPrMessages = [];
$installationOps = [];
$marketplaceOps = [];

if ($event === 'push') {
    if ($payload === 'commit-otherbranch' || $payload === 'commit-defaultbranch-noimages') {
        echo "ok\n";
    } elseif ($payload === 'commit-imgbotbranch') {
        $openPrMessages[] = ['InstallationId' => 23199, 'RepoName' => 'test', 'CloneUrl' => 'https://github.com/dabutvin/test'];
        echo "ok\n";
    } elseif ($payload === 'commit-defaultbranch-images') {
        $routerMessages[] = ['InstallationId' => 23199, 'Owner' => 'dabutvin', 'RepoName' => 'test', 'CloneUrl' => 'https://github.com/dabutvin/test'];
        echo "ok\n";
    }
} elseif ($event === 'installation_repositories') {
    if ($payload === 'installation_repositories_added') {
        $routerMessages[] = ['InstallationId' => 554, 'Owner' => 'dabutvin', 'RepoName' => 'testing', 'CloneUrl' => 'https://github.com/dabutvin/testing'];
        echo "ok\n";
    } elseif ($payload === 'installation_repositories_removed') {
        $installationOps[] = 'Delete';
        echo "ok\n";
    }
} elseif ($event === 'integration_installation') {
    if ($payload === 'installation_repositories_created') {
        $routerMessages[] = ['InstallationId' => 541, 'Owner' => 'dabutvin', 'RepoName' => 'myrepo', 'CloneUrl' => 'https://github.com/dabutvin/myrepo'];
        echo "ok\n";
    }
} elseif ($event === 'installation') {
    if ($payload === 'installation_deleted') {
        $installationOps[] = 'Delete';
        $installationOps[] = 'Delete';
        echo "ok\n";
    }
} elseif ($event === 'marketplace_purchase') {
    if ($payload === 'marketplacepurchase') {
        $marketplaceOps[] = 'InsertOrMerge';
        echo "purchased\n";
    } elseif ($payload === 'marketplacecancellation') {
        $marketplaceOps[] = 'Delete';
        echo "cancelled\n";
    }
}
?>
--EXPECT--
