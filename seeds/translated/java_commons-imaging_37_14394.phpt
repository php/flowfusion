--TEST--
Translated seed (inferredbugs-java: commons-imaging/37)
--FILE--
<?php

$jpegImageFile = 'input.jpg';
$dst = 'output.jpg';

function removeExifMetadata($jpegImageFile, $dst) {
    if (!file_exists($jpegImageFile)) {
        return;
    }
    copy($jpegImageFile, $dst);
}

function changeExifMetadata($jpegImageFile, $dst) {
    if (!file_exists($jpegImageFile)) {
        return;
    }
    copy($jpegImageFile, $dst);
}

function removeExifTag($jpegImageFile, $dst) {
    if (!file_exists($jpegImageFile)) {
        return;
    }
    copy($jpegImageFile, $dst);
}

function setExifGPSTag($jpegImageFile, $dst) {
    if (!file_exists($jpegImageFile)) {
        return;
    }
    copy($jpegImageFile, $dst);
}

removeExifMetadata($jpegImageFile, $dst);
changeExifMetadata($jpegImageFile, $dst);
removeExifTag($jpegImageFile, $dst);
setExifGPSTag($jpegImageFile, $dst);

?>
--EXPECT--
