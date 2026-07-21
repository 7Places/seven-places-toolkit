<?php
/**
 * Plugin Name: Seven Places Toolkit
 * Plugin URI: https://github.com/7Places/seven-places-toolkit
 * Description: Modern WordPress framework for reusable agency plugins.
 * Version: 0.1.7
 * Requires at least: 6.8
 * Requires PHP: 8.2
 * Author: Seven Places Productions + Jamon Abercrombie
 * Author URI: https://sevenplacesproductions.com
 * Text Domain: seven-places-toolkit
 *
 * GitHub Repository: 7Places/seven-places-toolkit
 * GitHub Branch: main
 * Release Channel: stable
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Plugin Activation
|--------------------------------------------------------------------------
|
| Queue a one-time admin notice after activation.
|
*/
register_activation_hook(
    __FILE__,
    static function (): void {
        Activator::activate(__FILE__);
    }
);

SPT\Core\Application::boot(__FILE__);
