--TEST--
Translated seed (inferredbugs-java: MediathekView/16)
--FILE--
<?php

class DownloadManager {
    private $downloads = [];
    private $runningDownloads = [];
    private $completedDownloads = [];

    public function addDownload($url) {
        $this->downloads[] = $url;
    }

    public function startDownload($url) {
        if (in_array($url, $this->downloads)) {
            $this->runningDownloads[] = $url;
            $this->downloads = array_diff($this->downloads, [$url]);
        }
    }

    public function completeDownload($url) {
        if (in_array($url, $this->runningDownloads)) {
            $this->completedDownloads[] = $url;
            $this->runningDownloads = array_diff($this->runningDownloads, [$url]);
        }
    }

    public function removeCompletedDownloads() {
        foreach ($this->completedDownloads as $url) {
            $this->runningDownloads = array_diff($this->runningDownloads, [$url]);
        }
        $this->completedDownloads = [];
    }

    public function getStatus() {
        return [
            "total" => count($this->downloads) + count($this->runningDownloads) + count($this->completedDownloads),
            "running" => count($this->runningDownloads),
            "completed" => count($this->completedDownloads),
            "queued" => count($this->downloads),
        ];
    }
}

$manager = new DownloadManager();
$manager->addDownload("http://example.com/video1");
$manager->addDownload("http://example.com/video2");
$manager->startDownload("http://example.com/video1");
$manager->completeDownload("http://example.com/video1");
$manager->removeCompletedDownloads();

$status = $manager->getStatus();
echo "Total: {$status['total']}, Running: {$status['running']}, Completed: {$status['completed']}, Queued: {$status['queued']}\n";
--EXPECT--
