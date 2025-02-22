<?php

namespace KhantNyar\ServiceExtender\Services;

use Illuminate\Support\Facades\Http;
use KhantNyar\ServiceExtender\Services\Contracts\ThirpartyApiInterface;

abstract class ApiService implements ThirpartyApiInterface
{
    protected static string $base;
    protected static string $apiKey;

    protected function makeRequest(string $method, string $endpoint, array $data = [])
    {
        $url = rtrim(static::$base, '/') . '/' . ltrim($endpoint, '/');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . static::$apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->$method($url, $data);

        return $this->handleResponse($response);
    }

    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("API request failed: " . $response->body(), $response->status());
    }

    public function get(string $endpoint, array $query = [])
    {
        return $this->makeRequest('get', $endpoint, $query);
    }

    public function post(string $endpoint, array $data)
    {
        return $this->makeRequest('post', $endpoint, $data);
    }

    public function put(string $endpoint, array $data)
    {
        return $this->makeRequest('put', $endpoint, $data);
    }

    public function delete(string $endpoint, array $data = [])
    {
        return $this->makeRequest('delete', $endpoint, $data);
    }
}
