<?php

namespace PlatformScience;

trait SuitabilityScoreTrait
{
    public function findFactorsExceptOne($n)
    {
        $str = '';
        for ($i = 2; $i <= ($n / 2); $i++) {
            if ($n % $i == 0) {
                $str .= "$i,";
            }
        }
        $str .= $n;
        return trim($str, ",");
    }

    public function findVowelsConsonants($str)
    {
        $str = strtolower($str);
        $vCount = $cCount = 0;
        for ($i = 0, $iMax = strlen($str); $i < $iMax; $i++) {
            //Checks whether a character is a vowel
            if ($str[$i] == 'a' || $str[$i] == 'e' || $str[$i] == 'i' || $str[$i] == 'o' || $str[$i] == 'u') {
                //Increments the vowel counter
                $vCount++;
            } //Checks whether a character is a consonant
            elseif ($str[$i] >= 'a' && $str[$i] <= 'z') {
                //Increments the consonant counter
                $cCount++;
            }
        }
        return ['vowelsCount' => $vCount, 'consonantsCount' => $cCount];
    }
}