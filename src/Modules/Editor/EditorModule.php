<?php

declare(strict_types=1);

namespace SPT\Modules\Editor;

use SPT\Contracts\ModuleInterface;
use SPT\Core\Application;
use SPT\Settings\SettingKeys;

final class EditorModule implements ModuleInterface
{
    public function __construct(
        private readonly Application $app,
    ) {
    }

    public function register(): void
    {
        if (! $this->app->settings()->enabled( SettingKeys::EDITOR_WIDTHS )) {
            return;
        }

        add_action(
            'enqueue_block_editor_assets',
            [$this, 'enqueueAssets']
        );
    }

    public function enqueueAssets(): void
    {
        wp_enqueue_style(
            'spt-editor-overrides',
            $this->app->assetUrl('css/editor-overrides.css'),
            [],
            $this->app->version()
        );
    }
}