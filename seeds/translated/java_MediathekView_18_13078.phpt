--TEST--
Translated seed (inferredbugs-java: MediathekView/18)
--FILE--
<?php

class DownloadManager {
    private $downloads = [];
    
    public function addDownload($url) {
        $this->downloads[] = ['url' => $url, 'status' => 'waiting'];
    }
    
    public function startDownload($index) {
        if (isset($this->downloads[$index])) {
            $this->downloads[$index]['status'] = 'downloading';
            echo "Starting download: " . $this->downloads[$index]['url'] . "\n";
        }
    }
    
    public function stopDownload($index) {
        if (isset($this->downloads[$index])) {
            $this->downloads[$index]['status'] = 'stopped';
            echo "Stopped download: " . $this->downloads[$index]['url'] . "\n";
        }
    }
    
    public function deleteDownload($index) {
        if (isset($this->downloads[$index])) {
            unset($this->downloads[$index]);
            echo "Deleted download at index: " . $index . "\n";
        }
    }
    
    public function listDownloads() {
        foreach ($this->downloads as $index => $download) {
            echo "Index: $index, URL: " . $download['url'] . ", Status: " . $download['status'] . "\n";
        }
    }
}

$manager = new DownloadManager();
$manager->addDownload("http://example.com/file1");
$manager->addDownload("http://example.com/file2");
$manager->startDownload(0);
$manager->stopDownload(0);
$manager->deleteDownload(1);
$manager->listDownloads();
--EXPECT--
