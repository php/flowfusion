--TEST--
Translated seed (inferredbugs-java: InflatableDonkey/6)
--FILE--
<?php

class DownloadAssistant {
    private $authorizeAssets;
    private $assetDownloader;
    private $keyBagManager;
    private $folder;

    public function __construct($authorizeAssets, $assetDownloader, $keyBagManager, $folder) {
        $this->authorizeAssets = $authorizeAssets;
        $this->assetDownloader = $assetDownloader;
        $this->keyBagManager = $keyBagManager;
        $this->folder = $folder;
    }

    public function download($httpClient, $assets, $relativePath) {
        $outputFolder = $this->folder . DIRECTORY_SEPARATOR . $relativePath;

        $this->keyBagManager->update($httpClient, $assets);

        $fileKeys = new XFileKeyFactory([$this->keyBagManager, 'keyBag']);
        $fileAssembler = new FileAssembler($fileKeys, $outputFolder);

        $authorizedAssets = $this->authorizeAssets->authorize($httpClient, $assets);

        $this->assetDownloader->accept($httpClient, $authorizedAssets, $fileAssembler);
    }
}

$authorizeAssets = new AuthorizeAssets();
$assetDownloader = new AssetDownloader();
$keyBagManager = new KeyBagManager();
$folder = '/path/to/output';

$downloader = new DownloadAssistant($authorizeAssets, $assetDownloader, $keyBagManager, $folder);
$httpClient = null; // Placeholder for an actual HTTP client
$assets = []; // Placeholder for actual assets
$relativePath = 'relative/path';

$downloader->download($httpClient, $assets, $relativePath);
--EXPECT--
