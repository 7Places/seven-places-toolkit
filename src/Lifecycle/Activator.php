<?php

declare(strict_types=1);

namespace SPT\Lifecycle;

final class Activator
{
    public static function activate(string $pluginFile): void
    {
        update_option(
            'spt_admin_notice',
            'activated'
        );
    }
}
