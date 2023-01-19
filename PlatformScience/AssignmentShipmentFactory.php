<?php

namespace PlatformScience;

/**
 * Factory class to calculate assignment
 */
class AssignmentShipmentFactory
{
    private $drivers;
    private $destination;

    public function __construct($drivers, $destination)
    {
        $this->drivers = $drivers;
        $this->destination = $destination;
    }

}