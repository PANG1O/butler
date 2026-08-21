<?php
declare(strict_types=1);

namespace Pangio\Core\Http;

use JetBrains\PhpStorm\NoReturn;

/**
 * Provides helper methods for sending standardized JSON API responses with appropriate HTTP status codes and
 * terminating execution.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class ApiResponse {
    public const int OK = 200;
    public const int CREATED = 201;
    public const int UNAUTHORIZED = 401;
    public const int FORBIDDEN = 403;
    public const int NOT_FOUND = 404;
    public const int METHOD_NOT_ALLOWED = 405;
    public const int CONFLICT = 409;
    public const int UNPROCESSABLE_ENTITY = 422;
    public const int INTERNAL_SERVER_ERROR = 500;

    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Sends a JSON response with HTTP 200 (OK) and terminates script execution.
     *
     * @param array $data
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function ok(array $data = [], string $message = 'OK'): void {
        Response::json(self::OK, [
            'message' => $message,
            'success' => true,
            'data' => $data
        ]);

        exit;
    }

    /**
     * Sends a JSON response with HTTP 201 (Created) and terminates script execution.
     *
     * @return void
     */
    #[NoReturn]
    public static function created(): void {
        Response::json(self::CREATED, []);
    }

    /**
     *  Sends a JSON response with HTTP 401 (Unauthorized) and terminates script execution.
     *
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function unauthorized(string $message = 'Unauthorized'): void {
        Response::json(self::UNAUTHORIZED, [
            'message' => $message,
            'success' => false,
            'data' => []
        ]);

        exit;
    }

    /**
     *  Sends a JSON response with HTTP 403 (Forbidden) and terminates script execution.
     *
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function forbidden(string $message = 'Forbidden'): void {
        Response::json(self::FORBIDDEN, [
            'message' => $message,
            'success' => false,
            'data' => []
        ]);

        exit;
    }

    /**
     * Sends a JSON response with HTTP 404 (Not Found) and terminates script execution.
     *
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function notFound(string $message = 'Not found'): void {
        Response::json(self::NOT_FOUND, [
            'message' => $message,
            'success' => false,
            'data' => []
        ]);

        exit;
    }

    /**
     * Sends a JSON response with HTTP 405 (Method Not Allowed) and terminates script execution.
     *
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function methodNotAllowed(string $message = 'Method Not Allowed'): void {
        Response::json(self::METHOD_NOT_ALLOWED, [
            'message' => $message,
            'success' => false,
            'data' => []
        ]);

        exit;
    }

    /**
     * Sends a JSON response with HTTP 409 (Conflict) and terminates script execution.
     *
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function conflict(string $message = 'Conflict'): void {
        Response::json(self::CONFLICT, [
            'message' => $message,
            'success' => false,
            'data' => []
        ]);

        exit;
    }

    /**
     * Sends a JSON response with HTTP 422 (Unprocessable Entity) and terminates script execution.
     *
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function unprocessableEntity(array $missing = [], string $message = 'Unprocessable Entity'): void {
        Response::json(self::UNPROCESSABLE_ENTITY, [
            'message' => $message,
            'success' => false,
            'data' => $missing
        ]);

        exit;
    }

    /**
     * Sends a JSON response with HTTP 500 (Internal Server Error) and terminates script execution.
     *
     * @param string $message
     * @return void
     */
    #[NoReturn]
    public static function internalServerError(string $message = 'Internal Server Error'): void {
        Response::json(self::INTERNAL_SERVER_ERROR, [
            'message' => $message,
            'success' => false,
            'data' => []
        ]);

        exit;
    }
}