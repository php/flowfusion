--TEST--
Translated seed (inferredbugs-csharp: Buildalyzer/1)
--FILE--
<?php

class ProjectAnalyzer {
    private $projectFile;
    private $log;

    public function __construct($projectFile, $log) {
        $this->projectFile = $projectFile;
        $this->log = $log;
    }

    public function load() {
        // Simulate loading a project
        return $this->projectFile ? new Project() : null;
    }

    public function compile() {
        // Simulate compiling a project
        return $this->projectFile ? new ProjectInstance() : null;
    }

    public function getSourceFiles() {
        // Simulate getting source files
        return ["Class1.cs", "Class2.cs"];
    }
}

class Project {}

class ProjectInstance {}

function getProjectAnalyzer($projectFile, &$log) {
    $projectPath = realpath(__DIR__ . "/../$projectFile");
    return new ProjectAnalyzer($projectPath, $log);
}

$projectFiles = [
    "LegacyFrameworkProject/LegacyFrameworkProject.csproj",
    "SdkNetCoreProject/SdkNetCoreProject.csproj",
    "SdkNetStandardProject/SdkNetStandardProject.csproj"
];

foreach ($projectFiles as $projectFile) {
    $log = "";
    
    $analyzer = getProjectAnalyzer($projectFile, $log);
    
    $project = $analyzer->load();
    if ($project === null) {
        $log .= "Failed to load project: $projectFile";
    }

    $projectInstance = $analyzer->compile();
    if ($projectInstance === null) {
        $log .= "Failed to compile project: $projectFile";
    }

    $sourceFiles = $analyzer->getSourceFiles();
    if (!in_array("Class1.cs", $sourceFiles)) {
        $log .= "Source files do not contain Class1.cs in project: $projectFile";
    }
}

?>
--EXPECT--
