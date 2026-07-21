<?php

declare(strict_types=1);

namespace SPT\Infrastructure\GitHub;

use SPT\Core\Application;

final readonly class GitHubInstaller
{
    public function __construct(
        private Application $app,
    ) {
    }

    public function register(): void
    {
        add_filter(
            'upgrader_source_selection',
            [$this, 'sourceSelection'],
            10,
            4
        );
    }

    public function sourceSelection(
        string $source,
        string $remoteSource,
        mixed $upgrader,
        array $hookExtra,
    ): string {

        error_log('SPT sourceSelection reached');

        if (
            !isset($hookExtra['plugin'])
            || $hookExtra['plugin'] !== $this->app->basename()
        ) {
            return $source;
        }

        $desired = trailingslashit($remoteSource . '/' . $this->app->slug());

        if ($source === $desired) {
            return $source;
        }

        global $wp_filesystem;

        if (!$wp_filesystem) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        if (!$wp_filesystem) {
            return $source;
        }

        if ($wp_filesystem->move($source, $desired, true)) {
            return $desired;
        }

        return $source;
    }

    public function packageOptions(array $options): array
    {
        return $options;
    }

}
