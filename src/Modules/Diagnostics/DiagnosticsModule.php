<?php

declare(strict_types=1);

namespace SPT\Modules\Diagnostics;

use SPT\Contracts\ModuleInterface;
use SPT\Core\Application;

final class DiagnosticsModule implements ModuleInterface
{
    public function __construct(
        private readonly Application $app
    ) {
    }

    public function register(): void
    {
        add_action('admin_notices', [$this, 'adminNotice']);
    }

    public function adminNotice(): void
    {
        printf(
            '<div class="notice notice-success"><p>Seven Places Toolkit initialized successfully (v%s)</p></div>',
            esc_html($this->app->version())
        );
    }
}
