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

    /**
     * Condition 1:
     * If the length of the shipment's destination street name is even, the base suitability
     * score (SS) is the number of vowels in the driver’s name multiplied by 1.5.
     * Condition 2:
     * If the length of the shipment's destination street name is odd, the base SS is the
     * number of consonants in the driver’s name multiplied by 1.
     */
    public function calculateBaseSS()
    {
        $counts = $this->findVowelsConsonants($this->driver->getName());
        $this->baseSS = (strlen($this->address->getAddress()) % 2 === 0) ? $counts['vowelsCount'] * 1.5 : $counts['consonantsCount'] * 1;
    }

    /**
     *If the length of the shipment's destination street name shares any common factors
     * (besides 1) with the length of the driver’s name, the SS is increased by 50% above the
     * base SS.
     */
    public function calculateFinalSS()
    {
        if ($this->baseSS === null) {
            $this->calculateBaseSS();
        }
        $factorsOfDestinationNameLengthInArr = explode(",", $this->findFactorsExceptOne(strlen($this->address->getAddress())));
        $factorsOfDriverNameLengthInArr = explode(",", $this->findFactorsExceptOne(strlen($this->driver->getName())));
        // share any common factors
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