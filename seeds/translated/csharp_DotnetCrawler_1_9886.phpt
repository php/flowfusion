--TEST--
Translated seed (inferredbugs-csharp: DotnetCrawler/1)
--FILE--
<?php

class DotnetCrawler {
    private $request;
    private $downloader;
    private $processor;
    private $pipeline;

    public function addRequest($request) {
        $this->request = $request;
        return $this;
    }

    public function addDownloader($downloader) {
        $this->downloader = $downloader;
        return $this;
    }

    public function addProcessor($processor) {
        $this->processor = $processor;
        return $this;
    }

    public function addPipeline($pipeline) {
        $this->pipeline = $pipeline;
        return $this;
    }

    public function crawl() {
        // Simulate a download with a regex check for the URL pattern
        if (preg_match($this->request['regex'], $this->request['url'])) {
            // Simulate a dummy download process
            $content = "Downloaded content from {$this->request['url']}";
            $this->process($content);
        }
    }

    private function process($content) {
        // Simulate processing the content
        $this->pipeline->execute($content);
    }
}

class DotnetCrawlerRequest {
    public $url;
    public $regex;
    public $timeout;
}

class DotnetCrawlerDownloader {
    public $downloaderType;
    public $downloadPath;
}

class DotnetCrawlerProcessor {
    // Placeholder for processing logic
}

class DotnetCrawlerPipeline {
    public function execute($content) {
        // Placeholder for pipeline logic
    }
}

$url = "https://www.ebay.com/b/Apple-iPhone/9355/bn_319682";

$crawler = (new DotnetCrawler())
    ->addRequest(['url' => $url, 'regex' => '/.*itm\/.+/', 'timeout' => 5000])
    ->addDownloader((new DotnetCrawlerDownloader())->setProperties(['downloaderType' => 'FromMemory', 'downloadPath' => '/tmp/']))
    ->addProcessor(new DotnetCrawlerProcessor())
    ->addPipeline(new DotnetCrawlerPipeline());

$crawler->crawl();
--EXPECT--
