<?php

declare(strict_types=1);

namespace SPT\Modules\Settings;

use SPT\Admin\AdminPage;
use SPT\Contracts\ModuleInterface;
use SPT\Core\Application;
use SPT\Settings\SettingKeys;

final class SettingsModule implements ModuleInterface
{
    public function __construct(
        private readonly Application $app,
    ) {
    }

    public function register(): void
    {
        $this->app->admin()->addPage(
            new AdminPage(
                title: 'General',
                menuTitle: 'Seven Places Toolkit',
                capability: 'manage_options',
                slug: 'seven-places-toolkit',
                callback: [$this, 'renderPage'],
                icon: 'dashicons-admin-tools',
                position: 81,
            )
        );
    }

    public function renderPage(): void
    {
        $this->handlePost();

        $this->app->views()->render(
            __DIR__ . '/views/settings.php',
            [
                'app'      => $this->app,
                'settings' => $this->app->settings(),
                'version'  => $this->app->version(),
                'logo_url' => $this->app->assetUrl('images/7places-circle.jpg'),
            ]
        );
    }

    private function handlePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        check_admin_referer('spt_settings');

        $this->app->settings()->set(
			SettingKeys::EDITOR_WIDTHS,
			isset($_POST[SettingKeys::EDITOR_WIDTHS])
		);

        add_settings_error(
            'spt',
            'settings_saved',
            'Settings saved.',
            'updated'
        );
    }
}
