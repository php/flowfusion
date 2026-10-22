--TEST--
Translated seed (inferredbugs-java: cli-spring-boot-scaffold/1)
--FILE--
<?php

class RepositoryGenerate {
    public function generate($name, $arg2, $template) {
        $filePath = "src/main/java/br/com/example/repository/{$name}Repository.java";
        file_put_contents($filePath, "<?php\n// Generated repository for {$name}\nclass {$name}Repository {}\n");
    }
}

function convertJavaToText($file, $layer, $outputFile) {
    $content = file_get_contents($file);
    file_put_contents($outputFile, $content);
    return $outputFile;
}

$repositoryGenerator = new RepositoryGenerate();
$repositoryGenerator->generate("User", null, "template-repository.txt");
$repositoryJavaFile = "src/main/java/br/com/example/repository/UserRepository.java";

$newTextFile = convertJavaToText($repositoryJavaFile, null, "UserRepositoryTest.txt");

$validateFileEquals = file_get_contents($newTextFile) === file_get_contents("src/test/resources/templates/java/repository/UserRepository.txt");

$repositoryJavaFileExists = file_exists($repositoryJavaFile);
$newTextFileExists = file_exists($newTextFile);

if ($repositoryJavaFileExists) unlink($repositoryJavaFile);
if ($newTextFileExists) unlink($newTextFile);
--EXPECT--
