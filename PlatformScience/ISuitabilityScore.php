<?php

namespace PlatformScience;

interface ISuitabilityScore
{
    public function calculateBaseSS();

    public function calculateFinalSS();
}