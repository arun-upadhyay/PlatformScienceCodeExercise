<?php

namespace PlatformScience;

/**
 * Address Model
 */
class Address
{
    private $address;

    public function __construct($address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }
}