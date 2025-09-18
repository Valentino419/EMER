<?php
// app/Traits/LicensePlateValidator.php

namespace App\Traits;

trait LicensePlateValidator
{
    /**
     * Validate and clean license plate
     */
    private function validateAndCleanLicensePlate(string $plate): array
    {
        // Convert to uppercase first, then remove non-alphanumeric characters
        $cleanedPlate = preg_replace('/[^A-Z0-9]/', '', strtoupper(trim($plate)));
        
        // Define regex patterns for each country
        $patterns = [
            'Argentina' => [
                '/^[A-Z]{2}\d{3}[A-Z]{2}$/',  // AA000AA
                '/^[A-Z]{3}\d{3}$/',          // AAA000
                '/^[A-Z]\d{6}$/'              // A000000
            ],
            'Brazil' => [
                '/^[A-Z]{3}\d[A-Z]\d{2}$/',   // AAA0A00
                '/^[A-Z]{3}\d{4}$/'           // AAA0000
            ],
            'Uruguay' => [
                '/^[A-Z]{3}\d{4}$/'           // AAA0000
            ]
        ];
        
        $isValid = false;
        $validCountry = null;
        
        foreach ($patterns as $country => $countryPatterns) {
            foreach ($countryPatterns as $pattern) {
                if (preg_match($pattern, $cleanedPlate)) {
                    $isValid = true;
                    $validCountry = $country;
                    break 2;
                }
            }
        }
        
        return [
            'valid' => $isValid,
            'cleaned' => $cleanedPlate,
            'country' => $validCountry,
            'original' => $plate
        ];
    }
}