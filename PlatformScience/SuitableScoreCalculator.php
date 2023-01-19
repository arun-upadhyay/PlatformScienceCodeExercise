<?php

namespace PlatformScience;

/**
 * Suitable score calculator
 */
class SuitableScoreCalculator implements ISuitabilityScore
{
    private $baseSS;
    private $finalSS;
    private $address;
    private $driver;
    use SuitabilityScoreTrait;

    /**
     * @param $destinationAddress
     * @param $driverName
     */
    public function __construct($destinationAddress = null, $driverName = null)
    {
        $this->address = new Address($destinationAddress);
        $this->driver = new Driver($driverName);
    }

    public function calculateBaseSS()
    {
        $counts = $this->findVowelsConsonants($this->driver->getName());
        $this->baseSS = (strlen($this->address->getAddress()) % 2 === 0) ? $counts['vowelsCount'] * 1.5 : $counts['consonantsCount'] * 1;
    }

    public function calculateFinalSS()
    {
        if ($this->baseSS === null) {
            $this->calculateBaseSS();
        }
        $factorsOfDestinationNameLengthInArr = explode(",", $this->findFactorsExceptOne(strlen($this->address->getAddress())));
        $factorsOfDriverNameLengthInArr = explode(",", $this->findFactorsExceptOne(strlen($this->driver->getName())));
        $arrayIntersect = array_intersect($factorsOfDestinationNameLengthInArr, $factorsOfDriverNameLengthInArr);
        $this->finalSS = empty($arrayIntersect) ? $this->baseSS : ($this->baseSS + 0.5 * $this->baseSS);
    }

    /**
     * Getter
     *
     * @return int
     */
    public function getBaseSS()
    {
        return $this->baseSS;
    }

    /**
     * getter method
     *
     * @return int
     */
    public function getFinalSS()
    {
        return $this->finalSS;
    }

    public function setAddress($address)
    {
        $this->address->setAddress($address);
    }

    public function setDriver($name)
    {
        $this->driver->setName($name);
    }
}