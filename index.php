<?php

use PlatformScience\SuitableScoreCalculator;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$faker = Faker\Factory::create();

file_put_contents($_ENV['DRIVER_FILE_NAME'], '');
file_put_contents($_ENV['DESTINATION_FILE_NAME'], '');
$names = $addresses = '';
for ($i = 0; $i < 5; $i++) {
    $names .= $faker->name . PHP_EOL;
    $addresses .= $faker->address() . PHP_EOL;
}
file_put_contents($_ENV['DRIVER_FILE_NAME'], trim($names, PHP_EOL));
file_put_contents($_ENV['DESTINATION_FILE_NAME'], trim($addresses, PHP_EOL));

$drivers = file_get_contents($_ENV['DRIVER_FILE_NAME']);
$drivers = explode(PHP_EOL, $drivers);
$destinations = file_get_contents($_ENV['DESTINATION_FILE_NAME']);
$destinations = explode(PHP_EOL, $destinations);

$arr = [];
foreach ($drivers as $driver) {
    $des = [];
    foreach ($destinations as $destination) {
        $suitableScoreCalculator = new SuitableScoreCalculator($destination, $driver);
        $suitableScoreCalculator->calculateFinalSS();
        $des[$destination] = $suitableScoreCalculator->getFinalSS();
    }
    arsort($des);
    foreach ($des as $address => $ssValue) {
        $arr[$driver][$address] = $ssValue;
    }
}

print_r($arr);



