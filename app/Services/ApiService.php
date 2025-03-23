<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected $crmApiUrl;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    /**
     * Make a GET request to the CRM API
     */
    public function get(string $token, string $endpoint)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . $endpoint);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('API request failed: ' . $response->body());
    }

    /**
     * Make a PUT request to the CRM API
     */
    public function put(string $token, string $endpoint, array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put($this->crmApiUrl . $endpoint, $data);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('API request failed: ' . $response->body());
    }

    /**
     * Make a DELETE request to the CRM API
     */
    public function delete(string $token, string $endpoint)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->delete($this->crmApiUrl . $endpoint);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('API request failed: ' . $response->body());
    }
}