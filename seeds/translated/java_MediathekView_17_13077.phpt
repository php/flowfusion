--TEST--
Translated seed (inferredbugs-java: MediathekView/17)
--FILE--
<?php

class DownloadManager {
    private $downloads = [];
    private $runningDownloads = [];

    public function addDownload($url) {
        $this->downloads[] = $url;
    }

    public function startAllDownloads() {
        foreach ($this->downloads as $url) {
            $this->startDownload($url);
        }
    }

    public function stopAllDownloads() {
        foreach ($this->runningDownloads as $url) {
            $this->stopDownload($url);
        }
    }

    private function startDownload($url) {
        if (!in_array($url, $this->runningDownloads)) {
            $this->runningDownloads[] = $url;
            echo "Started downloading from: $url\n";
        }
    }

    private function stopDownload($url) {
        if (($key = array_search($url, $this->runningDownloads)) !== false) {
            unset($this->runningDownloads[$key]);
            echo "Stopped downloading from: $url\n";
        }
    }

    public function removeCompletedDownloads() {
        foreach ($this->runningDownloads as $url) {
            // Simulate download completion
            echo "Completed downloading from: $url\n";
            $this->stopDownload($url);
        }
        $this->runningDownloads = [];
    }

    public function displayStatus() {
        $totalDownloads = count($this->downloads);
        $runningCount = count($this->runningDownloads);
        echo "$totalDownloads downloads total, $runningCount running.\n";
    }
}

$manager = new DownloadManager();
$manager->addDownload("http://example.com/file1");
$manager->addDownload("http://example.com/file2");
$manager->startAllDownloads();
$manager->displayStatus();
$manager->removeCompletedDownloads();
$manager->displayStatus();
$manager->stopAllDownloads();
--EXPECT--
