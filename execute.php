<?php

use PlatformScience\AssignmentShipmentFactory;
use PlatformScience\SuitableScoreCalculator;

require 'vendor/autoload.php';
// access for .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// Faker to generate name and address location
$faker = Faker\Factory::create();

file_put_contents($_ENV['DRIVER_FILE_NAME'], '');
file_put_contents($_ENV['DESTINATION_FILE_NAME'], '');
$names = $addresses = '';
for ($i = 0; $i < 10; $i++) {
    $names .= $faker->name . PHP_EOL;
    $addresses .= $faker->streetAddress . " " . $faker->city . " " . $faker->postcode . PHP_EOL;
}
file_put_contents($_ENV['DRIVER_FILE_NAME'], trim($names, PHP_EOL));
file_put_contents($_ENV['DESTINATION_FILE_NAME'], trim($addresses, PHP_EOL));

$drivers = file_get_contents($_ENV['DRIVER_FILE_NAME']);
$drivers = explode(PHP_EOL, $drivers);
$destinations = file_get_contents($_ENV['DESTINATION_FILE_NAME']);
$destinations = explode(PHP_EOL, $destinations);

$driverLocationSSList = [];
$sortedSSData = [];
foreach ($drivers as $driver) {
    $des = [];
    foreach ($destinations as $destination) {
        $suitableScoreCalculator = new SuitableScoreCalculator($destination, $driver);
        $suitableScoreCalculator->calculateFinalSS();
        $des[$destination] = $suitableScoreCalculator->getFinalSS();
        $sortedSSData[] = $suitableScoreCalculator->getFinalSS();
    }
    // sort in descending order
    arsort($des);
    foreach ($des as $address => $ssValue) {
        $driverLocationSSList[$driver][$address] = $ssValue;
    }
}
// sort in descending order
rsort($sortedSSData);
$assignmentShipmentFactory = new AssignmentShipmentFactory($driverLocationSSList, $sortedSSData);
$assignmentShipmentFactory->generate();