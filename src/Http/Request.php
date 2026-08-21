<?php
declare(strict_types=1);

namespace Pangio\Core\Http;

use Pangio\Core\Infrastructure\Logger;
use RuntimeException;
use JsonException;

/**
 * Provides a static HTTP request utility for accessing and validating GET and POST data as well as determining the
 * current request method.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class Request {
    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Retrieves HTTP method (POST, GET, etc.)
     *
     * @return string
     */
    public static function method(): string {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Retrieves GET parameter.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed {
        return $_GET[$key] ?? $default;
    }

    /**
     * Retrieves POST parameter.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function post(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $default;
    }

    /**
     * Retrieves all request data (GET & POST)
     *
     * @return array
     */
    public static function all(): array {
        return array_merge($_GET, $_POST);
    }

    /**
     * Validates that all required request fields are present and not empty, returning false if any are missing.
     *
     * @param array $requiredFields
     * @param string $method
     * @return bool
     */
    public static function validate(array $requiredFields, $method = 'POST'): bool {
        $input = $method === 'POST' ? $_POST : $_GET;

        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieves a specific HTTP header value from the server environment, normalizing its name and returning a default
     * if not present.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function header(string $key, mixed $default = null): mixed {
        $key = strtoupper(str_replace('-', '_', $key));
        $serverKey = 'HTTP_' . $key;

        return $_SERVER[$serverKey] ?? $default;
    }

    /**
     * Parses the raw HTTP request body as JSON and returns it as an array, logging and throwing an exception on failure.
     *
     * @return array
     */
    public static function parseJsonBody(): array {
        try {
            return json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            Logger::log('error', '[Request::getInput()] ' . $exception->getMessage());

            throw new RuntimeException('[Request::getInput()] ' . $exception->getMessage());
        }
    }

    /**
     * Extracts and returns the Bearer token from the Authorization header, or an empty string if not present or invalid.
     *
     * @return string
     */
    public static function bearerToken(): string {
        $authHeader = self::header('Authorization');
        return $authHeader && str_starts_with($authHeader, 'Bearer ') ? substr($authHeader, 7) : '';
    }
}