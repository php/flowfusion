--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/9)
--FILE--
<?php

class ColorSpace {
    const CS_sRGB = 5000;
    const CS_LINEAR_RGB = 5001;

    public static function getInstance($colorSpace) {
        return new self($colorSpace);
    }

    private $type;

    private function __construct($type) {
        $this->type = $type;
    }

    public function fromRGB(array $rgb) {
        // Emulating some conversion
        return $rgb;
    }
}

class ICC_Profile {
    const icSigHead = 0;
    const icHdrRenderingIntent = 1;

    private $header;

    public function __construct($header) {
        $this->header = $header;
    }

    public static function getInstance($data) {
        return new self($data);
    }

    public function getData($type) {
        return $this->header;
    }

    public function getColorSpaceType() {
        return $this->header[0];
    }
}

function createColorSpace($profile) {
    $header = $profile->getData(ICC_Profile::icSigHead);

    if ($profile->getColorSpaceType() === ColorSpace::CS_sRGB && $header[0] === 0) {
        return ColorSpace::getInstance(ColorSpace::CS_sRGB);
    }

    if ($header[ICC_Profile::icHdrRenderingIntent] !== 0) {
        $header[ICC_Profile::icHdrRenderingIntent] = 0;
        // Emulate fixing and checking the profile
    }

    // Emulate creating or caching a color space
    $cs = new ColorSpace($profile->getColorSpaceType());
    return $cs;
}

$profileData = [ColorSpace::CS_sRGB, 0]; // Simulating a valid ICC profile header
$profile = ICC_Profile::getInstance($profileData);
$colorSpace = createColorSpace($profile);
$colorSpace->fromRGB([1.0, 0.0, 0.0]);
--EXPECT--
