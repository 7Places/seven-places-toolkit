<?php

declare(strict_types=1);

namespace SPT\Modules\Diagnostics;

use SPT\Contracts\ModuleInterface;
use SPT\Core\Application;
use SPT\Admin\AdminPage;
use SPT\Admin\AdminMenu;

final class DiagnosticsModule implements ModuleInterface
{
    public function __construct(
        private readonly Application $app,
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

        $this->app->admin()->addPage(
            new AdminPage(
                title: 'Diagnostics',
                menuTitle: 'Diagnostics',
                capability: 'manage_options',
                slug: 'spt-diagnostics',
                callback: [$this, 'renderPage'],
				parentSlug: AdminMenu::ROOT_SLUG,
            )
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
        $status = get_option('spt_admin_notice');

        if (!$status) {
            return;
        }

        delete_option('spt_admin_notice');

        $message = match ($status) {
            'activated' => sprintf(
                'Seven Places Toolkit activated successfully (v%s).',
                $this->app->version()
            ),
            'updated' => sprintf(
                'Seven Places Toolkit updated successfully (v%s).',
                $this->app->version()
            ),
            default => sprintf(
                'Seven Places Toolkit initialized successfully (v%s).',
                $this->app->version()
            ),
        };

        printf(
            '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
            esc_html($message)
        );
    }


    private function diagnostics(): array
    {
        return [
            'framework' => [
                'Name'         => $this->app->name(),
                'Version'      => $this->app->version(),
                'Plugin File'  => $this->app->pluginFile(),
                'Plugin Path'  => $this->app->pluginPath(),
                'Plugin URL'   => $this->app->pluginUrl(),
                'Text Domain'  => $this->app->textDomain(),
                'Slug'         => $this->app->slug(),
            ],

            'environment' => [
                'PHP Version'        => PHP_VERSION,
                'WordPress Version'  => get_bloginfo('version'),
                'WP_DEBUG'           => WP_DEBUG ? 'Yes' : 'No',
                'Multisite'          => is_multisite() ? 'Yes' : 'No',
                'Memory Limit'       => WP_MEMORY_LIMIT,
            ],

            'settings' => $this->app->settings()->all(),
        ];
    }


    public function renderPage(): void
    {
        $this->app->views()->render(
            __DIR__ . '/views/diagnostics.php',
            [
                'diagnostics' => $this->diagnostics(),
            ]
        );
    }


}
