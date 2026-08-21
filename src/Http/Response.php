<?php
declare(strict_types=1);

namespace Pangio\Core\Http;

use JetBrains\PhpStorm\NoReturn;
use RuntimeException;
use JsonException;

/**
 * Provides a static HTTP response utility for sending plain text or JSON responses and handling redirects with
 * appropriate status codes.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class Response {
    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Sends a plain text response.
     *
     * @param string $content
     * @param int $statusCode
     * @return void
     */
    public static function send(string $content, int $statusCode = 200): void {
        http_response_code($statusCode);
        echo $content;
    }

    /**
     * Sends a JSON response.
     *
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    public static function json(int $statusCode, array $data): void {
        http_response_code($statusCode);

        header('Content-Type: application/json');

        try {
            echo json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('[Response::json] ' . $exception->getMessage());
        }
    }

    /**
     * Redirects to another URL.
     *
     * @param string $url
     * @param int $statusCode
     * @return void
     */
    #[NoReturn]
    public static function redirect(string $url, int $statusCode = 302): void {
        http_response_code($statusCode);
        header("Location: $url");
        exit;
    }
}