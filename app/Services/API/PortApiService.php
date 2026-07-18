<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortApiService
{
    /**
     * Fetch port data from World Port Index public dataset
     * 
     * Menggunakan JSON dataset publik dari World Port Index
     * Data source: https://msi.nga.mil/api/publications/world-port-index
     */
    public function fetchPorts(): array
    {
        try {
            // World Port Index API endpoint (public JSON)
            $url = 'https://msi.nga.mil/api/publications/world-port-index';

            Log::info('Fetching ports from World Port Index API...');

            $response = Http::timeout(60)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                // World Port Index returns array of ports directly
                if (isset($data['ports']) && is_array($data['ports'])) {
                    Log::info('Successfully fetched ' . count($data['ports']) . ' ports from API');
                    return $data['ports'];
                }
                
                // Jika format response berbeda, return data langsung
                if (is_array($data)) {
                    Log::info('Successfully fetched ' . count($data) . ' ports from API');
                    return $data;
                }

                Log::warning('Unexpected API response format');
                return [];
            }

            Log::error('World Port Index API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];

        } catch (\Exception $e) {
            Log::error('Error fetching ports from World Port Index', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

    /**
     * Alternative: Fetch from static JSON file (fallback)
     * 
     * Jika API tidak tersedia, bisa pakai static JSON yang sudah di-download
     */
    public function fetchPortsFromStaticFile(): array
    {
        try {
            $jsonPath = storage_path('app/data/world-ports.json');

            if (!file_exists($jsonPath)) {
                Log::warning('Static port data file not found at: ' . $jsonPath);
                return [];
            }

            $jsonContent = file_get_contents($jsonPath);
            $data = json_decode($jsonContent, true);

            if (isset($data['ports']) && is_array($data['ports'])) {
                Log::info('Successfully loaded ' . count($data['ports']) . ' ports from static file');
                return $data['ports'];
            }

            if (is_array($data)) {
                Log::info('Successfully loaded ' . count($data) . ' ports from static file');
                return $data;
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Error loading ports from static file', [
                'message' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Get port data (try API first, fallback to static file)
     */
    public function getPorts(): array
    {
        // Try API first
        $ports = $this->fetchPorts();

        // Fallback to static file if API fails
        if (empty($ports)) {
            Log::info('API fetch failed, trying static file...');
            $ports = $this->fetchPortsFromStaticFile();
        }

        return $ports;
    }
}
