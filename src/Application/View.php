<?php
declare(strict_types=1);

namespace Pangio\Core\Application;

use Pangio\Core\System\Session;
use RuntimeException;

/**
 * Provides a static utility for rendering PHP view templates with injected parameters and session-based flash messages,
 * returning the generated output as a string.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class View {
    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Renders a view file with optional parameters and returns the generated output as a string.
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    public static function render(string $view, array $params = []): string {
        $viewDirectory = dirname(__DIR__, 2) . '/app/Views/';
        $viewPath = $viewDirectory . '/' . $view . '.php';

        if (!is_file($viewPath)) {
            throw new RuntimeException("View not found: $viewPath");
        }

        if (Session::has('flashMessage')) {
            $params['flashMessage'] = Session::getFlashMessage();
        }

        extract($params, EXTR_SKIP);

        ob_start();
        include $viewPath;

        $content = ob_get_clean();

        return $content !== false ? $content : '';
    }
}