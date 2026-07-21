<?php

declare(strict_types=1);

namespace SPT\Modules\Diagnostics;

use SPT\Core\Application;

final class DiagnosticsData
{
    public function __construct(
        private readonly Application $app,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return [
            'framework' => [
                'Name'        => $this->app->name(),
                'Version'     => $this->app->version(),
                'Plugin File' => $this->app->pluginFile(),
                'Plugin Path' => $this->app->pluginPath(),
                'Plugin URL'  => $this->app->pluginUrl(),
                'Text Domain' => $this->app->textDomain(),
                'Slug'        => $this->app->slug(),
            ],

            'environment' => [
                'PHP Version'        => PHP_VERSION,
                'WordPress Version'  => get_bloginfo('version'),
                'WP_DEBUG'           => WP_DEBUG ? 'Yes' : 'No',
                'Multisite'          => is_multisite() ? 'Yes' : 'No',
                'Memory Limit'       => WP_MEMORY_LIMIT,
            ],

            'settings' => $this->app
                ->settings()
                ->all(),
        ];
    }
}
