--TEST--
Translated seed (inferredbugs-csharp: Downloader/5)
--FILE--
<?php

class DownloadItem {
    public $folderPath;
    public $url;
    public $fileName;

    public function __construct($folderPath, $url, $fileName = null) {
        $this->folderPath = $folderPath;
        $this->url = $url;
        $this->fileName = $fileName;
    }
}

class DownloadService {
    public function downloadFile($url, $filePath) {
        $this->simulateDownload($url, $filePath);
    }

    private function simulateDownload($url, $filePath) {
        // Simulate download progress for demonstration
        for ($i = 0; $i <= 100; $i+=10) {
            usleep(100000); // Sleep for 0.1 second to simulate download time
            echo "Downloading " . basename($filePath) . ": $i%\n";
        }
    }
}

function main() {
    $downloadListFile = "DownloadList.json";
    $downloadList = getDownloadItems($downloadListFile);

    foreach ($downloadList as $downloadItem) {
        $ds = new DownloadService();
        $filePath = $downloadItem->fileName ?: $downloadItem->folderPath . '/' . basename($downloadItem->url);
        $ds->downloadFile($downloadItem->url, $filePath);
    }
}

function getDownloadItems($filename) {
    if (file_exists($filename)) {
        $jsonData = file_get_contents($filename);
        $downloadList = json_decode($jsonData);
    } else {
        $downloadList[] = new DownloadItem(sys_get_temp_dir(), "http://ipv4.download.thinkbroadband.com/100MB.zip");
    }
    return $downloadList;
}

main();
--EXPECT--
