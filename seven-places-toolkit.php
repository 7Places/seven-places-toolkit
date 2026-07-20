<?php
/**
 * Plugin Name: Seven Places Toolkit
 * Plugin URI: https://github.com/7Places/seven-places-toolkit
 * Description: Professional toolkit for WordPress agencies.
 * Version: 0.2.0
 * Requires at least: 6.8
 * Requires PHP: 8.2
 * Author: Seven Places Productions + Jamon Abercrombie
 * License: Proprietary
 * Text Domain: seven-places-toolkit
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

SPT\Core\Application::boot(__FILE__);
