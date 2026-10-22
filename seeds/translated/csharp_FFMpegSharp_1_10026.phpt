--TEST--
Translated seed (inferredbugs-csharp: FFMpegSharp/1)
--FILE--
<?php

class VideoInfo {}

class BitmapExtensions {
    public static function addAudio($poster, $audio, $output) {
        $destination = time() . '.png';

        imagepng($poster, $destination);

        return new VideoInfo(); // Simulate FFMpeg's processing
    }
}

// Example usage
$width = 100;
$height = 100;
$poster = imagecreatetruecolor($width, $height);
$audio = 'audio-file.mp3'; // Placeholder for audio input
$output = 'output-file.mp4'; // Placeholder for output

$videoInfo = BitmapExtensions::addAudio($poster, $audio, $output);
imagedestroy($poster); // Clean up the image resource

?>
--EXPECT--
