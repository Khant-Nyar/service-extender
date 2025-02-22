<?php

namespace KhantNyar\ServiceExtender\Services\Contracts;

interface ThirpartyApiInterface {
    public function get(string $endpoint, array $query = []);

    public function post(string $endpoint, array $data);

    public function put(string $endpoint, array $data);

    public function delete(string $endpoint, array $data = []);
}
