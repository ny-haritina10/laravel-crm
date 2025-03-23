<?php

namespace App\Services;

class AlerteRateService
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function getAllAlerteRates(string $token)
    {
        return $this->apiService->get($token, "/api/alerte-rates");
    }

    /**
     * Get an AlerteRate by ID
     */
    public function getAlerteRate(string $token, $id)
    {
        return $this->apiService->get($token, "/api/alerte-rates/{$id}");
    }

    /**
     * Update an AlerteRate
     */
    public function updateAlerteRate(string $token, $id, array $data)
    {
        return $this->apiService->put($token, "/api/alerte-rates/{$id}", $data);
    }

    /**
     * Delete an AlerteRate
     */
    public function deleteAlerteRate(string $token, $id)
    {
        return $this->apiService->delete($token, "/api/alerte-rates/{$id}");
    }
}