<?php
declare(strict_types=1);

namespace Pangio\Core\Http;

use Pangio\Core\Infrastructure\JsonSerializer;
use Pangio\Core\System\Config;

/**
 * Lightweight HTTP client for sending JSON-based API requests (GET, POST, PUT, DELETE) and returning decoded JSON
 * responses as arrays.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class ApiRequest {
    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Sends a GET request to a URL and returns the decoded JSON response as an array.
     *
     * @param string $url
     * @return array
     */
    public static function get(string $url): array {
        $secret = Config::getSecret();
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n" .
                    "Authorization: Basic $secret\r\n"
            ]
        ];

        $context = stream_context_create($options);

        return JsonSerializer::decode(file_get_contents($url, false, $context));
    }

    /**
     * Sends a POST request with a JSON payload and returns the decoded JSON response as an array.
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    public static function post(string $url, array $data): array {
        $secret = Config::getSecret();
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n" .
                    "Authorization: Basic $secret\r\n",
                'content' => JsonSerializer::encode($data)
            ]
        ];

        $context = stream_context_create($options);

        return JsonSerializer::decode(file_get_contents($url, false, $context));
    }

    /**
     * Sends a PUT request with a JSON payload and returns the decoded JSON response as an array.
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    public static function put(string $url, array $data): array {
        $secret = Config::getSecret();
        $options = [
            'http' => [
                'method' => 'PUT',
                'header' => "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n" .
                    "Authorization: Basic $secret\r\n",
                'content' => JsonSerializer::encode($data)
            ]
        ];

        $context = stream_context_create($options);

        return JsonSerializer::decode(file_get_contents($url, false, $context));
    }

    /**
     * Sends a DELETE request and returns the decoded JSON response as an array.
     *
     * @param string $url
     * @return array
     */
    public static function delete(string $url): array {
        $secret = Config::getSecret();
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n" .
                    "Authorization: Basic $secret\r\n"
            ]
        ];

        $context = stream_context_create($options);

        return JsonSerializer::decode(file_get_contents($url, false, $context));
    }
}