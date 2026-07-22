<?php

declare(strict_types=1);

namespace SPT\Infrastructure\GitHub;

use SPT\Core\Application;

final readonly class GitHubUpdater
{
    public function __construct(
        private Application $app,
    ) {
    }

    public function check(): UpdateInfo
    {
        $release = $this->app
            ->github()
            ->latestRelease();

        $currentVersion = $this->app->version();

        $latestVersion = ltrim(
            (string) ($release['tag_name'] ?? ''),
            'v'
        );

        return new UpdateInfo(
            available: version_compare($latestVersion, $currentVersion, '>'),
            currentVersion: $currentVersion,
            latestVersion: $latestVersion,
            releaseName: (string) ($release['name'] ?? $latestVersion),
            downloadUrl: (string) ($release['zipball_url'] ?? ''),
            releaseUrl: (string) ($release['html_url'] ?? ''),
            publishedAt: (string) ($release['published_at'] ?? ''),
            releaseNotes: (string) ($release['body'] ?? ''),
        );
    }
}
