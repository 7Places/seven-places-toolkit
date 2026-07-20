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
        add_action(
            'admin_notices',
            [$this, 'adminNotice']
        );

        add_action(
            'admin_enqueue_scripts',
            [$this, 'enqueueAssets']
        );
    }

    public function enqueueAssets(): void
    {
        $this->app->assets()->enqueueStyle(
            'spt-diagnostics',
            'css/diagnostics.css'
        );
    }

    public function adminNotice(): void
    {
        printf(
            '<div class="notice notice-success spt-diagnostics-notice"><p>Seven Places Toolkit initialized successfully (v%s)</p></div>',
            esc_html($this->app->version())
        );
    }
}
