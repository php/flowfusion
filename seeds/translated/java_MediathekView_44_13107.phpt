--TEST--
Translated seed (inferredbugs-java: MediathekView/44)
--FILE--
<?php
class Download {
    public $url;
    public $status; // 0: waiting, 1: downloading, 2: finished
    public function __construct($url) {
        $this->url = $url;
        $this->status = 0;
    }
}

class DownloadManager {
    private $downloads = [];
    
    public function addDownload($url) {
        $this->downloads[] = new Download($url);
    }

    public function startAll() {
        foreach ($this->downloads as $download) {
            if ($download->status == 0) {
                $download->status = 1; // set to downloading
                echo "Starting download: " . $download->url . "\n";
            }
        }
    }

    public function stopAll() {
        foreach ($this->downloads as $download) {
            if ($download->status == 1) {
                $download->status = 0; // set to waiting
                echo "Stopping download: " . $download->url . "\n";
            }
        }
    }

    public function updateDownloads() {
        foreach ($this->downloads as $download) {
            if ($download->status == 1) {
                // Simulate download completion
                $download->status = 2;
                echo "Finished download: " . $download->url . "\n";
            }
        }
    }

    public function getDownloadStatus() {
        foreach ($this->downloads as $download) {
            echo "Status of " . $download->url . ": " . ($download->status == 0 ? "waiting" : ($download->status == 1 ? "downloading" : "finished")) . "\n";
        }
    }
}

$manager = new DownloadManager();
$manager->addDownload("http://example.com/file1");
$manager->addDownload("http://example.com/file2");
$manager->startAll();
$manager->updateDownloads();
$manager->getDownloadStatus();
$manager->stopAll();
$manager->getDownloadStatus();
--EXPECT--
