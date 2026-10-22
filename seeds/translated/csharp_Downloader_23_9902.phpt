--TEST--
Translated seed (inferredbugs-csharp: Downloader/23)
--FILE--
<?php

class DownloadItem {
    public $FolderPath;
    public $Url;
    public $FileName;

    public function __construct($folderPath, $url, $fileName = null) {
        $this->FolderPath = $folderPath;
        $this->Url = $url;
        $this->FileName = $fileName;
    }
}

class DownloadService {
    public function downloadFileAsync($url, $folderPath) {
        // Simulate download behavior
        sleep(2); // Simulating delay
        echo "Downloaded {$url} to {$folderPath}\n";
    }
}

function getDownloadItems() {
    return [
        new DownloadItem(sys_get_temp_dir(), "http://ipv4.download.thinkbroadband.com/100MB.zip")
    ];
}

function downloadAll($downloadList) {
    foreach ($downloadList as $downloadItem) {
        $ds = new DownloadService();
        $ds->downloadFileAsync($downloadItem->Url, $downloadItem->FolderPath);
    }
}

function main() {
    $downloadList = getDownloadItems();
    downloadAll($downloadList);
    echo "END\n";
}

main();
--EXPECT--
