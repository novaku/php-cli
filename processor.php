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
        echo isset($options['dst']) ? : Common::errorMissingParamMessage('dst');
        echo isset($options['src']) ? : Common::errorMissingParamMessage('src');
    } else {
        $currentDir     = getcwd() . DIRECTORY_SEPARATOR;
        $sourceDir      = "{$currentDir}{$options['src']}";
        $destinationDir = "{$currentDir}{$options['dst']}";

        $files = Common::getDirContents($sourceDir);

        foreach ($files as $file) {
            echo Common::notificationMessage($file);
            $newFile = str_replace($sourceDir, $destinationDir, $file);

            try {
                Common::copyFile($file, $newFile);
                echo Common::notificationMessage("===> {$newFile}");
            } catch (Exception $exception) {
                echo Common::errorMessage($exception->getMessage());
            }
        }
    }
}
