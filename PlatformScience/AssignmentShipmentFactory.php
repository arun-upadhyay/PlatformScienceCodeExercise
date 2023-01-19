<?php

namespace PlatformScience;

/**
 * Class to assign driver to location based on highest SS score.
 */
class AssignmentShipmentFactory
{
    private $assignedDriverList = [];
    private $assignedLocations = [];
    private $driverLocationSSList;
    private $sortSSValues;
    private $totalSSCalculated;

    /**
     * @param array $driverLocationSSList
     * @param array $sortSSValues
     */
    public function __construct(array $driverLocationSSList, array $sortSSValues)
    {
        /**
         * [Lupe Ondricka] => Array
         * (
         * [189 Esteban Square Suite 279 Lolaborough 58004-5517] => 7
         * [16173 Zieme Avenue Suite 655 Kilbackmouth 33971] => 7
         * [3848 Armstrong View Apt. 227 Weldonton 81670-7116] => 7
         * [56741 Chanel Canyon Virgilbury 95808-8076] => 7
         * [12773 Reinger Corners Nameberg 77590-2393] => 7
         * )
         * [Terry Hills] => Array
         * (
         * [189 Esteban Square Suite 279 Lolaborough 58004-5517] => 8
         * [16173 Zieme Avenue Suite 655 Kilbackmouth 33971] => 8
         * [3848 Armstrong View Apt. 227 Weldonton 81670-7116] => 8
         * [56741 Chanel Canyon Virgilbury 95808-8076] => 8
         * [12773 Reinger Corners Nameberg 77590-2393] => 8
         * )
         * Contains list driver with their location and corresponding SS score calculated.
         */
        $this->driverLocationSSList = $driverLocationSSList;
        /**
         * Sorted order of all the SS scores in descending order.
         */
        $this->sortSSValues = $sortSSValues;
        $this->totalSSCalculated = 0;
    }

    /**
     * I'm assuming that driver name and location are unique here. This function with traverse throughout the list of SS Score (sorted in descending order).
     * It will pick the highest SS score first and find the driver and location associated with it. If SS score match, it will be assigned to driver, and it's location
     * to assignedDriverList array and next highest SS score will be search until all drivers are allocated with shipment location.
     * Drawback:
     * This is probably not the best solution here. It might have random cases where same SS score result
     */
    public function generate()
    {
        foreach ($this->sortSSValues as $value) {
            if (count($this->assignedDriverList) >= count($this->driverLocationSSList)) {
                break;
            }
            $this->findAddressAndDriver($value);
        }
        echo "\n\e[0;30;47m Total Suitability Score (SS): {$this->totalSSCalculated}  \e[0m\n";
        echo "\n\e[0;30;47m All drivers with corresponding shipment location and SS Score.  \e[0m\n";
        foreach ($this->assignedDriverList as $driver => $locationSSArr) {
            echo "\n\nDriver's Name: {$driver} \nLocation for Shipment: {$locationSSArr["address"]}\nSuitability Score (SS): {$locationSSArr["ssScore"]} \e[0m\n";
        }
    }

    /**
     * @param $value
     */
    private function findAddressAndDriver($value)
    {
        foreach ($this->driverLocationSSList as $driver => $addressesSS) {
            if (isset($this->assignedDriverList[$driver])) {
                // Resource is allocated to this driver
                continue;
            }
            foreach ($addressesSS as $address => $ss) {
                if (in_array($address, $this->assignedLocations)) {
                    // this location address is already assigned to some driver. Use another address location
                    continue;
                }
                if ($ss == $value) {
                    $this->assignedDriverList[$driver] = ["address" => $address, "ssScore" => $value];
                    $this->assignedLocations[] = $address;
                    $this->totalSSCalculated += $value;
                    break;
                }
            }
        }
    }
}