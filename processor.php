<?php
include_once("functions.php");

$colors = new Colors();

if (in_array('help', $argv)) {
    $filename = basename(__FILE__);

    echo $colors->setColoredString("Available Parameters :", "light_red", "black") . PHP_EOL;
    echo $colors->setColoredString("1. --src => Define the source root folder to convert all files (.html)", "light_green", "black") . PHP_EOL;
    echo $colors->setColoredString("2. --dst => Define the destination root folder to copy all the results from source folder", "light_green", "black") . PHP_EOL;
    echo $colors->setColoredString("Example usage : 'php {$filename} --src source --dst destination'", "light_green", "black") . PHP_EOL;
} else {
    $longOpts = [
        "dst:",
        "src:",
    ];
    $options  = getopt("", $longOpts);

    if (!isset($options['dst']) || !isset($options['src'])) {
        echo isset($options['dst']) ? : Main::errorMissingParamMessage('dst');
        echo isset($options['src']) ? : Main::errorMissingParamMessage('src');
    } else {
        $currentDir     = getcwd() . DIRECTORY_SEPARATOR;
        $sourceDir      = "{$currentDir}{$options['src']}";
        $destinationDir = "{$currentDir}{$options['dst']}";

        $files = Main::getDirContents($sourceDir);

        foreach ($files as $filePath) {
            Main::notificationMessage($filePath);
            $newFile = str_replace($sourceDir, $destinationDir, $filePath);

            try {
                Main::copyAndManipulateFile($filePath, $newFile, "http://acme.test/", ["partner" => "widget co"]);
                Main::notificationMessage("===> {$newFile}");
            } catch (Exception $exception) {
                Main::errorMessage($exception->getMessage());
            }
        }
    }
}
