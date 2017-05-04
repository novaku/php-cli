<?php
include_once("functions.php");

$colors = new Colors();

if (in_array('help', $argv)) {
    $filename = basename(__FILE__);

    echo $colors->setColoredString("Available Parameters :", "light_red", "black") . PHP_EOL;
    echo $colors->setColoredString("1. --src => Define the source root folder to convert all files (.html)", "light_green", "black") . PHP_EOL;
    echo $colors->setColoredString("2. --dst => Define the destination root folder to copy all the results from source folder", "light_green", "black") . PHP_EOL;
    echo $colors->setColoredString("Example usage : 'php {$filename} --src /folder1/root --dst /folder2/root'", "light_green", "black") . PHP_EOL;
} else {
    $longOpts = [
        "dst:",
        "src:",
    ];
    $options  = getopt("", $longOpts);

    if (!isset($options['dst']) || !isset($options['src'])) {
        echo !isset($options['dst']) ? Common::errorMessage('dst') : "";
        echo !isset($options['src']) ? Common::errorMessage('src') : "";
    } else {
        var_dump($options);
    }
}
