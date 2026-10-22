--TEST--
Translated seed (inferredbugs-csharp: Captura/6)
--FILE--
<?php

interface IImageProvider {
    public function getWidth();
    public function getHeight();
    public function capture();
}

interface IAudioProvider {
    public function getWaveFormat();
}

interface IAudioFileWriter {}

interface IVideoFileWriter {
    public function writeFrame(IBitmapFrame $frame);
    public function supportsAudio();
}

interface IBitmapFrame {
    public function dispose();
}

class OneTimeFrame implements IBitmapFrame {
    private $width;
    private $height;
    
    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function dispose() {
        // Simulate resource cleanup
    }
}

class ImageProviderMock implements IImageProvider {
    private $width;
    private $height;

    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function capture() {
        return new OneTimeFrame($this->width, $this->height);
    }
}

class AudioProviderMock implements IAudioProvider {
    public function getWaveFormat() {
        return new stdClass(); // Simulating a wave format object
    }
}

class VideoFileWriterMock implements IVideoFileWriter {
    public function writeFrame(IBitmapFrame $frame) {
        $frame->dispose();
    }

    public function supportsAudio() {
        return true;
    }
}

function main() {
    $imageProvider = new ImageProviderMock(100, 50);
    $audioProvider = new AudioProviderMock();
    $videoWriter = new VideoFileWriterMock();

    $frame = $imageProvider->capture();
    $videoWriter->writeFrame($frame);
}

main();
--EXPECT--
