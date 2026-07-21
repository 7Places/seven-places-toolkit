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
        $currentVersion = $this->app
            ->metadata()
            ->version();

        $release = $this->app
            ->github()
            ->latestRelease();

        $latestVersion = ltrim(
            (string) ($release['tag_name'] ?? ''),
            'v'
        );

        return new UpdateInfo(
            currentVersion: $this->app->version(),
            latestVersion: $latestVersion,
            available: version_compare($latestVersion, $this->app->version(), '>'),
            downloadUrl: (string) ($release['zipball_url'] ?? ''),
            releaseUrl: (string) ($release['html_url'] ?? ''),
            publishedAt: (string) ($release['published_at'] ?? ''),
            releaseNotes: (string) ($release['body'] ?? ''),
        );
    }

}
