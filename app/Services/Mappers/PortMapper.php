<?php

namespace App\Services\Mappers;

use App\Models\Country;
use Illuminate\Support\Facades\Log;

class PortMapper
{
    /**
     * Map port data from World Port Index format to our database format
     * 
     * @param array $portData - Raw data from API
     * @return array|null - Mapped data or null if invalid
     */
    public function map(array $portData): ?array
    {
        try {
            // Extract data from World Port Index format
            // Format bisa berbeda tergantung source, sesuaikan dengan response actual
            
            $portName = $this->extractPortName($portData);
            $countryName = $this->extractCountryName($portData);
            $latitude = $this->extractLatitude($portData);
            $longitude = $this->extractLongitude($portData);

            // Validasi data wajib
            if (empty($portName) || $latitude === null || $longitude === null) {
                Log::warning('Invalid port data: missing required fields', [
                    'port_name' => $portName,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);
                return null;
            }

            // Find country by name
            $country = null;
            if (!empty($countryName)) {
                $country = Country::where('name', 'like', "%{$countryName}%")->first();
                
                // Try alternative country matching
                if (!$country) {
                    $country = $this->findCountryByAlternativeName($countryName);
                }
            }

            return [
                'country_id' => $country?->id,
                'port_name' => $portName,
                'port_code' => $this->extractPortCode($portData),
                'city' => $this->extractCity($portData),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'type' => $this->extractType($portData),
            ];

        } catch (\Exception $e) {
            Log::error('Error mapping port data', [
                'message' => $e->getMessage(),
                'data' => $portData
            ]);

            return null;
        }
    }

    /**
     * Extract port name from various possible field names
     */
    protected function extractPortName(array $data): ?string
    {
        return $data['wpi_port_name']
            ?? $data['point_of_interest']
            ?? $data['portName'] 
            ?? $data['port_name'] 
            ?? $data['name'] 
            ?? $data['Main_Port_Name']
            ?? $data['PORT_NAME']
            ?? null;
    }

    /**
     * Extract country name
     */
    protected function extractCountryName(array $data): ?string
    {
        return $data['country'] 
            ?? $data['countryName'] 
            ?? $data['country_name']
            ?? $data['Country']
            ?? $data['COUNTRY']
            ?? null;
    }

    /**
     * Extract latitude
     */
    protected function extractLatitude(array $data): ?float
    {
        $lat = $data['latitude'] 
            ?? $data['lat'] 
            ?? $data['Latitude']
            ?? $data['LATITUDE']
            ?? null;

        return $lat !== null ? (float) $lat : null;
    }

    /**
     * Extract longitude
     */
    protected function extractLongitude(array $data): ?float
    {
        $lon = $data['longitude'] 
            ?? $data['lon'] 
            ?? $data['lng']
            ?? $data['Longitude']
            ?? $data['LONGITUDE']
            ?? null;

        return $lon !== null ? (float) $lon : null;
    }

    /**
     * Extract port code
     */
    protected function extractPortCode(array $data): ?string
    {
        return $data['port_code']
            ?? $data['portCode'] 
            ?? $data['code']
            ?? $data['wpi_port_id']
            ?? $data['World_Port_Index_Number']
            ?? null;
    }

    /**
     * Extract city
     */
    protected function extractCity(array $data): ?string
    {
        return $data['city'] 
            ?? $data['City']
            ?? null;
    }

    /**
     * Extract port type
     */
    protected function extractType(array $data): ?string
    {
        return $data['type']
            ?? $data['portType']
            ?? $data['port_type']
            ?? 'seaport'; // default
    }

    /**
     * Find country by alternative names
     */
    protected function findCountryByAlternativeName(string $countryName): ?Country
    {
        // Common country name variations
        $alternatives = [
            'United States' => ['USA', 'US', 'America'],
            'United Kingdom' => ['UK', 'Britain', 'Great Britain'],
            'South Korea' => ['Korea, South', 'Republic of Korea'],
            'Netherlands' => ['Holland'],
            'United Arab Emirates' => ['UAE'],
            // Tambah mapping lain jika perlu
        ];

        foreach ($alternatives as $standard => $variations) {
            if (in_array($countryName, $variations)) {
                return Country::where('name', 'like', "%{$standard}%")->first();
            }
        }

        return null;
    }
}
