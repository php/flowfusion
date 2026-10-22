--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/1)
--FILE--
<?php
class Version {
    private $segments;

    public function __construct($segments) {
        $this->segments = $segments;
    }

    public static function create($version) {
        return new self(explode('.', $version));
    }

    public function getNormalizedSegments() {
        return $this->segments;
    }

    public function equals($version) {
        return $this->segments === $version->getNormalizedSegments();
    }

    public function compareTo($version) {
        return version_compare(implode('.', $this->segments), implode('.', $version->getNormalizedSegments()));
    }

    public function __toString() {
        return implode('.', $this->segments);
    }
}

function normalizeVersion($version) {
    $segments = $version->getNormalizedSegments();
    return Version::create(implode('.', [
        $segments[0] ?? '1',
        $segments[1] ?? '0',
        $segments[2] ?? '0'
    ]));
}

$groupName = 'com.example';
$name = 'package';
$version = '1.0.0';
$currentLatestVersion = Version::create($version);
$versionEligible = true;

$existingVersions = [
    '1.0.0',
    '1.1.0',
    '2.0.0'
];

foreach ($existingVersions as $existingVersion) {
    $packageVersion = Version::create($existingVersion);
    
    if ($packageVersion->compareTo($currentLatestVersion) >= 1) {
        $currentLatestVersion = $packageVersion;
    } elseif ($packageVersion->equals($currentLatestVersion)) {
        $versionEligible = false;
    }
}

if ($versionEligible && $currentLatestVersion->equals($currentLatestVersion)) {
    // return config version
    $nextVersion = $currentLatestVersion;
} else {
    // Increment minor
    $segments = $currentLatestVersion->getNormalizedSegments();
    $segments[1] = (int)$segments[1] + 1;
    $nextVersion = Version::create(implode('.', $segments));
}

echo $nextVersion;
--EXPECT--
